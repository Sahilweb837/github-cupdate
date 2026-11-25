<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNotification;
use App\Models\VideoCall;
use App\Models\WebrtcSignal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VideoCallController extends Controller
{
    public function index(Request $request): View
    {
        $me = $request->user();
        $partnerId = $request->query('with');
        $partner = $partnerId ? User::find($partnerId) : null;

        $callHistory = VideoCall::where('caller_id', $me->id)
            ->orWhere('receiver_id', $me->id)
            ->with(['caller', 'receiver'])
            ->latest('created_at')
            ->take(15)
            ->get();

        $contacts = User::where('id', '!=', $me->id)
            ->where('status', 'active')
            ->where('is_admin', false)
            ->orderByDesc('is_online')
            ->take(10)
            ->get();

        return view('app.video-call', [
            'me' => $me,
            'partner' => $partner,
            'callHistory' => $callHistory,
            'contacts' => $contacts,
        ]);
    }

    public function start(Request $request): JsonResponse
    {
        $me = $request->user();
        $data = $request->validate([
            'receiver_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $call = VideoCall::create([
            'caller_id' => $me->id,
            'receiver_id' => $data['receiver_id'],
            'channel_name' => 'cup_' . Str::random(24),
            'status' => 'calling',
            'created_at' => now(),
        ]);

        UserNotification::create([
            'user_id' => $data['receiver_id'],
            'from_user_id' => $me->id,
            'type' => 'video_call',
            'title' => 'Incoming Video Call',
            'content' => "{$me->firstName()} is calling you on CupDate Video.",
            'is_read' => false,
            'created_at' => now(),
        ]);

        return response()->json(['call' => $call]);
    }

    public function incoming(Request $request): JsonResponse
    {
        $me = $request->user();
        $call = VideoCall::where('receiver_id', $me->id)
            ->where('status', 'calling')
            ->where('created_at', '>=', now()->subMinutes(2))
            ->with('caller:id,full_name,avatar,city')
            ->latest('created_at')
            ->first();

        return response()->json(['call' => $call]);
    }

    public function updateStatus(Request $request, VideoCall $call): JsonResponse
    {
        $me = $request->user();
        abort_unless($call->caller_id === $me->id || $call->receiver_id === $me->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:accepted,rejected,ended'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
        ]);

        $updates = ['status' => $data['status']];
        if ($data['status'] === 'accepted' && ! $call->started_at) {
            $updates['started_at'] = now();
        }
        if ($data['status'] === 'ended' || $data['status'] === 'rejected') {
            $updates['ended_at'] = now();
            if (isset($data['duration_seconds'])) {
                $updates['duration_seconds'] = $data['duration_seconds'];
            }
        }

        $call->update($updates);

        return response()->json(['ok' => true, 'call' => $call]);
    }

    public function sendSignal(Request $request, VideoCall $call): JsonResponse
    {
        $me = $request->user();
        abort_unless($call->caller_id === $me->id || $call->receiver_id === $me->id, 403);

        $data = $request->validate([
            'type' => ['required', 'in:offer,answer,ice'],
            'payload' => ['required', 'string'],
        ]);

        $signal = WebrtcSignal::create([
            'video_call_id' => $call->id,
            'sender_id' => $me->id,
            'type' => $data['type'],
            'payload' => $data['payload'],
        ]);

        return response()->json(['ok' => true, 'id' => $signal->id]);
    }

    public function pollSignals(Request $request, VideoCall $call): JsonResponse
    {
        $me = $request->user();
        abort_unless($call->caller_id === $me->id || $call->receiver_id === $me->id, 403);

        $afterId = (int) $request->query('after', 0);
        $signals = WebrtcSignal::where('video_call_id', $call->id)
            ->where('sender_id', '!=', $me->id)
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get();

        return response()->json([
            'call_status' => $call->status,
            'signals' => $signals,
        ]);
    }
}
