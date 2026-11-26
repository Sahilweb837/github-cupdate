<?php

namespace App\Http\Controllers;

use App\Models\UserReport;
use App\Models\VideoQueue;
use App\Services\LiveStatsService;
use App\Services\SafetyModerationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LiveVideoController extends Controller
{
    public function index(Request $request, LiveStatsService $liveStats): View
    {
        $stats = $liveStats->snapshot();
        $me = $request->user();

        return view('pages.live-video', [
            'me' => $me,
            'stats' => $stats,
        ]);
    }

    public function join(Request $request): JsonResponse
    {
        $data = $request->validate([
            'gender' => ['nullable', 'in:male,female,nonbinary,other'],
            'target_gender' => ['nullable', 'in:everyone,female,male'],
            'country' => ['nullable', 'string', 'max:60'],
        ]);

        $me = $request->user();
        $peerId = 'peer_' . Str::random(20);
        $gender = $data['gender'] ?? ($me?->gender ?? 'other');
        $targetGender = $data['target_gender'] ?? 'everyone';
        $country = $data['country'] ?? 'all';

        // Remove stale entries from this session/user
        if ($oldId = $request->session()->get('live_queue_id')) {
            $this->cleanupEntry((int) $oldId);
        }

        $entry = VideoQueue::create([
            'user_id' => $me?->id,
            'guest_name' => $me ? $me->firstName() : ('Guest_' . random_int(1000, 9999)),
            'peer_id' => $peerId,
            'gender' => $gender,
            'target_gender' => $targetGender,
            'country' => $country,
            'status' => 'waiting',
            'created_at' => now(),
        ]);

        $request->session()->put('live_queue_id', $entry->id);

        $match = $this->attemptMatch($entry);

        return response()->json([
            'ok' => true,
            'queue_id' => $entry->id,
            'peer_id' => $peerId,
            'match' => $match,
            'waiting_count' => VideoQueue::where('status', 'waiting')->where('created_at', '>=', now()->subMinutes(5))->count(),
        ]);
    }

    public function poll(Request $request): JsonResponse
    {
        $queueId = $this->resolveOwnedQueueId($request, (int) $request->query('queue_id', 0));
        $entry = $queueId ? VideoQueue::find($queueId) : null;

        if (! $entry) {
            return response()->json(['active' => false, 'match' => null, 'signals' => []]);
        }

        $match = Cache::get("cupdate:vq_match:{$entry->id}");
        if (! $match && $entry->status === 'waiting') {
            $match = $this->attemptMatch($entry);
        }

        $signals = [];
        $messages = [];
        $ended = false;

        if ($match && ! empty($match['room_id'])) {
            $roomKey = "cupdate:vq_room:{$match['room_id']}";
            $room = Cache::get($roomKey, ['signals' => [], 'messages' => [], 'ended' => false]);
            $afterSignal = (int) $request->query('after_signal', 0);
            $afterMsg = (int) $request->query('after_msg', 0);

            foreach ($room['signals'] ?? [] as $sig) {
                if (($sig['id'] ?? 0) > $afterSignal && ($sig['sender_queue_id'] ?? 0) !== $entry->id) {
                    $signals[] = $sig;
                }
            }
            foreach ($room['messages'] ?? [] as $msg) {
                if (($msg['id'] ?? 0) > $afterMsg) {
                    $messages[] = $msg;
                }
            }
            $ended = (bool) ($room['ended'] ?? false);
        }

        return response()->json([
            'active' => true,
            'match' => $match,
            'signals' => $signals,
            'messages' => $messages,
            'ended' => $ended,
            'waiting_count' => VideoQueue::where('status', 'waiting')->where('created_at', '>=', now()->subMinutes(5))->count(),
        ]);
    }

    public function signal(Request $request): JsonResponse
    {
        $queueId = $this->resolveOwnedQueueId($request, (int) $request->input('queue_id', 0));
        $match = $queueId ? Cache::get("cupdate:vq_match:{$queueId}") : null;

        if (! $match || empty($match['room_id'])) {
            return response()->json(['ok' => false, 'error' => 'No active live room.'], 404);
        }

        $data = $request->validate([
            'type' => ['required', 'in:offer,answer,ice'],
            'payload' => ['required', 'string', 'max:20000'],
        ]);

        $roomKey = "cupdate:vq_room:{$match['room_id']}";
        $room = Cache::get($roomKey, ['signals' => [], 'messages' => [], 'ended' => false]);
        $nextId = count($room['signals']) + 1;
        $room['signals'][] = [
            'id' => $nextId,
            'sender_queue_id' => $queueId,
            'type' => $data['type'],
            'payload' => $data['payload'],
        ];
        Cache::put($roomKey, $room, 600);

        return response()->json(['ok' => true, 'id' => $nextId]);
    }

    public function chat(Request $request, SafetyModerationService $safety): JsonResponse
    {
        $queueId = $this->resolveOwnedQueueId($request, (int) $request->input('queue_id', 0));
        $match = $queueId ? Cache::get("cupdate:vq_match:{$queueId}") : null;

        if (! $match || empty($match['room_id'])) {
            return response()->json(['ok' => false, 'error' => 'Not connected to a peer yet.'], 422);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:400'],
        ]);

        $screen = $safety->screenText($data['message']);
        if (! $screen['allowed']) {
            return response()->json(['ok' => false, 'error' => $screen['reason']], 422);
        }

        $entry = VideoQueue::find($queueId);
        $roomKey = "cupdate:vq_room:{$match['room_id']}";
        $room = Cache::get($roomKey, ['signals' => [], 'messages' => [], 'ended' => false]);
        $nextId = count($room['messages']) + 1;
        $msg = [
            'id' => $nextId,
            'sender_queue_id' => $queueId,
            'sender_name' => $entry?->guest_name ?? 'Guest',
            'text' => trim($data['message']),
            'time' => now()->format('g:i A'),
        ];
        $room['messages'][] = $msg;
        Cache::put($roomKey, $room, 600);

        return response()->json(['ok' => true, 'message' => $msg]);
    }

    public function leave(Request $request): JsonResponse
    {
        $queueId = $this->resolveOwnedQueueId($request, (int) $request->input('queue_id', 0));
        if ($queueId) {
            $this->cleanupEntry($queueId);
            $request->session()->forget('live_queue_id');
        }

        return response()->json(['ok' => true]);
    }

    public function report(Request $request): JsonResponse
    {
        $queueId = $this->resolveOwnedQueueId($request, (int) $request->input('queue_id', 0));
        $match = $queueId ? Cache::get("cupdate:vq_match:{$queueId}") : null;

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:150'],
        ]);

        if ($request->user() && ! empty($match['partner_user_id'])) {
            UserReport::create([
                'reporter_id' => $request->user()->id,
                'reported_id' => (int) $match['partner_user_id'],
                'reason' => 'Live Video: ' . $data['reason'],
                'content_snapshot' => 'Reported during random live video chat',
                'status' => 'pending',
                'created_at' => now(),
            ]);
        }

        if ($queueId) {
            $this->cleanupEntry($queueId);
        }

        return response()->json(['ok' => true, 'message' => 'Reported and disconnected safely.']);
    }

    private function resolveOwnedQueueId(Request $request, int $requestedId = 0): int
    {
        $sessionId = (int) $request->session()->get('live_queue_id', 0);
        if ($sessionId > 0 && $requestedId > 0 && $sessionId !== $requestedId) {
            return 0;
        }

        return $sessionId ?: $requestedId;
    }

    private function attemptMatch(VideoQueue $entry): ?array
    {
        return DB::transaction(function () use ($entry) {
            $fresh = VideoQueue::whereKey($entry->id)->lockForUpdate()->first();
            if (! $fresh || $fresh->status !== 'waiting') {
                return Cache::get("cupdate:vq_match:{$entry->id}");
            }

            $candidateQuery = VideoQueue::where('status', 'waiting')
                ->where('id', '!=', $fresh->id)
                ->where('created_at', '>=', now()->subMinutes(5));

            if ($fresh->user_id) {
                $candidateQuery->where(function ($q) use ($fresh) {
                    $q->whereNull('user_id')->orWhere('user_id', '!=', $fresh->user_id);
                });
            }

            if ($fresh->target_gender !== 'everyone') {
                $candidateQuery->where('gender', $fresh->target_gender);
            }

            if ($fresh->country !== 'all') {
                $candidateQuery->where(function ($q) use ($fresh) {
                    $q->where('country', 'all')->orWhere('country', $fresh->country);
                });
            }

            $partner = $candidateQuery->orderBy('id')->lockForUpdate()->first();
            if (! $partner) {
                return null;
            }

            $roomId = 'room_' . Str::random(18);
            $fresh->update(['status' => 'matched']);
            $partner->update(['status' => 'matched']);

            Cache::put("cupdate:vq_room:{$roomId}", ['signals' => [], 'messages' => [], 'ended' => false], 600);

            $matchForFresh = [
                'room_id' => $roomId,
                'initiator' => true,
                'partner_queue_id' => $partner->id,
                'partner_user_id' => $partner->user_id,
                'partner_name' => $partner->guest_name ?: 'Member',
                'partner_country' => $partner->country === 'all' ? 'Global' : $partner->country,
            ];

            $matchForPartner = [
                'room_id' => $roomId,
                'initiator' => false,
                'partner_queue_id' => $fresh->id,
                'partner_user_id' => $fresh->user_id,
                'partner_name' => $fresh->guest_name ?: 'Member',
                'partner_country' => $fresh->country === 'all' ? 'Global' : $fresh->country,
            ];

            Cache::put("cupdate:vq_match:{$fresh->id}", $matchForFresh, 600);
            Cache::put("cupdate:vq_match:{$partner->id}", $matchForPartner, 600);

            return $matchForFresh;
        });
    }

    private function cleanupEntry(int $queueId): void
    {
        $match = Cache::pull("cupdate:vq_match:{$queueId}");
        if ($match && ! empty($match['room_id'])) {
            $roomKey = "cupdate:vq_room:{$match['room_id']}";
            $room = Cache::get($roomKey, ['signals' => [], 'messages' => [], 'ended' => false]);
            $room['ended'] = true;
            Cache::put($roomKey, $room, 120);
        }

        VideoQueue::where('id', $queueId)->delete();
    }
}
