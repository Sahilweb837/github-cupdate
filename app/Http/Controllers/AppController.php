<?php

namespace App\Http\Controllers;

use App\Models\ChatRequest;
use App\Models\City;
use App\Models\CoffeeDate;
use App\Models\DatePlace;
use App\Models\InterestCategory;
use App\Models\Message;
use App\Models\Swipe;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserNotification;
use App\Models\UserReport;
use App\Models\VideoCall;
use App\Services\SafetyModerationService;
use App\Support\SqlSafe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AppController extends Controller
{
    public function discover(Request $request): View
    {
        $me = $request->user();
        $blockedIds = UserBlock::where('blocker_id', $me->id)->pluck('blocked_id')
            ->merge(UserBlock::where('blocked_id', $me->id)->pluck('blocker_id'))
            ->unique()
            ->push($me->id);

        $query = User::query()
            ->whereNotIn('id', $blockedIds)
            ->where('status', 'active')
            ->where('profile_visible', true)
            ->where('is_admin', false)
            ->where('email', 'not like', '%@cupdate.in')
            ->whereNotIn('role', User::ADMIN_ROLES);

        if ($city = $request->query('city')) {
            $query->where('city', $city);
        }
        if ($interest = $request->query('interest')) {
            $query->where('interests', 'like', '%' . SqlSafe::escapeLike((string) $interest) . '%');
        }
        if ($search = $request->query('q')) {
            $safe = SqlSafe::escapeLike((string) $search);
            $query->where(function ($q) use ($safe) {
                $q->where('full_name', 'like', "%{$safe}%")
                    ->orWhere('city', 'like', "%{$safe}%")
                    ->orWhere('bio', 'like', "%{$safe}%");
            });
        }

        $sort = $request->query('sort', 'nearby');
        match ($sort) {
            'new' => $query->latest('created_at'),
            'popular' => $query->orderByDesc('is_verified')->orderByDesc('is_online'),
            'random' => $query->inRandomOrder(),
            default => $query->orderByRaw('CASE WHEN city = ? THEN 0 ELSE 1 END', [$me->city])->orderByDesc('is_online'),
        };

        $profiles = $query->paginate(9)->withQueryString();
        $mySwipes = Swipe::where('swiper_id', $me->id)->pluck('type', 'swipee_id');

        return view('app.discover', [
            'me' => $me,
            'profiles' => $profiles,
            'mySwipes' => $mySwipes,
            'cities' => City::where('is_active', true)->orderBy('sort_order')->get(),
            'interests' => InterestCategory::where('is_active', true)->orderBy('name')->get(),
            'unreadNotifications' => UserNotification::where('user_id', $me->id)->where('is_read', false)->count(),
        ]);
    }

    public function swipe(Request $request): JsonResponse|RedirectResponse
    {
        $me = $request->user();
        $data = $request->validate([
            'target_id' => ['required', 'integer', 'exists:users,id'],
            'action' => ['required', 'in:like,dislike,superlike,request'],
            'note' => ['nullable', 'string', 'max:250'],
        ]);

        $target = User::findOrFail($data['target_id']);
        $swipeType = $data['action'] === 'request' ? 'superlike' : $data['action'];

        Swipe::updateOrCreate(
            ['swiper_id' => $me->id, 'swipee_id' => $target->id],
            ['type' => $swipeType, 'created_at' => now()]
        );

        $matched = false;

        if ($swipeType !== 'dislike') {
            ChatRequest::updateOrCreate(
                ['user_id' => $target->id, 'sender_id' => $me->id],
                [
                    'sender_name' => $me->full_name,
                    'sender_avatar' => $me->avatarUrl(),
                    'message' => $data['note'] ?? "Hi {$target->firstName()}, would love to connect over coffee!",
                    'status' => 'pending',
                    'created_at' => now(),
                ]
            );

            $reciprocal = Swipe::where('swiper_id', $target->id)
                ->where('swipee_id', $me->id)
                ->whereIn('type', ['like', 'superlike'])
                ->exists();

            if ($reciprocal) {
                $matched = true;
                $u1 = min($me->id, $target->id);
                $u2 = max($me->id, $target->id);
                DB::table('matches')->updateOrInsert(
                    ['user1_id' => $u1, 'user2_id' => $u2],
                    ['created_at' => now()]
                );

                UserNotification::create([
                    'user_id' => $target->id,
                    'from_user_id' => $me->id,
                    'type' => 'match',
                    'title' => 'New CupDate Match!',
                    'content' => "You and {$me->firstName()} both liked each other.",
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            } else {
                UserNotification::create([
                    'user_id' => $target->id,
                    'from_user_id' => $me->id,
                    'type' => 'friend_request',
                    'title' => 'New Connection Request',
                    'content' => "{$me->firstName()} from {$me->city} sent you a connection request.",
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'matched' => $matched,
                'action' => $data['action'],
            ]);
        }

        return back()->with('success', $matched ? "It's a connection with {$target->firstName()}!" : 'Preference saved.');
    }

    public function messages(Request $request): View
    {
        $me = $request->user();
        $blockedIds = UserBlock::where('blocker_id', $me->id)->pluck('blocked_id')
            ->merge(UserBlock::where('blocked_id', $me->id)->pluck('blocker_id'))
            ->unique()
            ->push($me->id);

        $partnerIds = Message::where('sender_id', $me->id)->pluck('receiver_id')
            ->merge(Message::where('receiver_id', $me->id)->pluck('sender_id'))
            ->merge(ChatRequest::where('user_id', $me->id)->pluck('sender_id'))
            ->merge(ChatRequest::where('sender_id', $me->id)->pluck('user_id'))
            ->unique()
            ->reject(fn ($id) => $blockedIds->contains($id));

        if ($partnerIds->isEmpty()) {
            $partners = User::whereNotIn('id', $blockedIds)
                ->where('status', 'active')
                ->where('is_admin', false)
                ->take(8)
                ->get();
        } else {
            $partners = User::whereIn('id', $partnerIds)->where('status', 'active')->get();
        }

        $activePartnerId = (int) $request->query('with', $partners->first()?->id ?? 0);
        $activePartner = $partners->firstWhere('id', $activePartnerId) ?? $partners->first();

        $thread = collect();
        if ($activePartner) {
            Message::where('sender_id', $activePartner->id)
                ->where('receiver_id', $me->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $thread = Message::where(function ($q) use ($me, $activePartner) {
                $q->where('sender_id', $me->id)->where('receiver_id', $activePartner->id)->where('deleted_by_sender', false);
            })->orWhere(function ($q) use ($me, $activePartner) {
                $q->where('sender_id', $activePartner->id)->where('receiver_id', $me->id)->where('deleted_by_receiver', false);
            })->orderBy('created_at')->get();
        }

        $recentCalls = VideoCall::where('caller_id', $me->id)
            ->orWhere('receiver_id', $me->id)
            ->with(['caller', 'receiver'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $lastMessages = collect();
        $unreadCounts = collect();
        if ($partners->isNotEmpty()) {
            $ids = $partners->pluck('id')->all();
            $recentMsgs = Message::where(function ($q) use ($me, $ids) {
                $q->where('sender_id', $me->id)->whereIn('receiver_id', $ids)->where('deleted_by_sender', false);
            })->orWhere(function ($q) use ($me, $ids) {
                $q->whereIn('sender_id', $ids)->where('receiver_id', $me->id)->where('deleted_by_receiver', false);
            })->latest('created_at')->get();

            foreach ($recentMsgs as $m) {
                $pid = $m->sender_id === $me->id ? $m->receiver_id : $m->sender_id;
                if (! $lastMessages->has($pid)) {
                    $lastMessages->put($pid, $m);
                }
                if ($m->receiver_id === $me->id && ! $m->is_read) {
                    $unreadCounts->put($pid, ($unreadCounts->get($pid, 0)) + 1);
                }
            }
        }

        return view('app.messages', [
            'me' => $me,
            'partners' => $partners,
            'activePartner' => $activePartner,
            'thread' => $thread,
            'recentCalls' => $recentCalls,
            'lastMessages' => $lastMessages,
            'unreadCounts' => $unreadCounts,
        ]);
    }

    public function sendMessage(Request $request, User $partner, SafetyModerationService $safety): JsonResponse|RedirectResponse
    {
        $me = $request->user();
        $isBlocked = UserBlock::where(function ($q) use ($me, $partner) {
            $q->where('blocker_id', $me->id)->where('blocked_id', $partner->id);
        })->orWhere(function ($q) use ($me, $partner) {
            $q->where('blocker_id', $partner->id)->where('blocked_id', $me->id);
        })->exists();

        if ($isBlocked) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Cannot message blocked user.'], 403)
                : back()->withErrors(['message' => 'Cannot message this user.']);
        }

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:3072'],
        ]);

        if (blank($data['message'] ?? null) && ! $request->hasFile('image')) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Write a message or choose an image to send.'], 422)
                : back()->withErrors(['message' => 'Write a message or choose an image to send.']);
        }

        $textCheck = $safety->screenText($data['message'] ?? '');
        if (! $textCheck['allowed']) {
            return $request->expectsJson()
                ? response()->json(['error' => $textCheck['reason']], 422)
                : back()->withErrors(['message' => $textCheck['reason']]);
        }

        $imageFlagged = false;
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imgCheck = $safety->screenImage($file);
            if (! $imgCheck['allowed']) {
                return $request->expectsJson()
                    ? response()->json(['error' => $imgCheck['reason']], 422)
                    : back()->withErrors(['image' => $imgCheck['reason']]);
            }
            $imageFlagged = $imgCheck['flagged'];
            $dir = public_path('uploads/chat');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = 'chat_' . $me->id . '_' . Str::random(10) . '.' . SqlSafe::safeImageExtension($file);
            $file->move($dir, $filename);
            $imagePath = 'uploads/chat/' . $filename;
        }

        $msg = Message::create([
            'sender_id' => $me->id,
            'receiver_id' => $partner->id,
            'message' => $data['message'] ?? '',
            'image_path' => $imagePath,
            'is_flagged' => $textCheck['flagged'] || $imageFlagged,
            'is_read' => false,
            'created_at' => now(),
        ]);

        UserNotification::create([
            'user_id' => $partner->id,
            'from_user_id' => $me->id,
            'type' => 'message',
            'title' => "New message from {$me->firstName()}",
            'content' => $imagePath ? 'Sent a photo' : mb_strimwidth((string) $msg->message, 0, 80, '...'),
            'is_read' => false,
            'created_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => [
                    'id' => $msg->id,
                    'body' => $msg->message,
                    'image_url' => $msg->image_path ? asset($msg->image_path) : null,
                    'time' => $msg->created_at?->format('g:i A') ?? 'Just now',
                ],
            ]);
        }

        return redirect()->route('app.messages', ['with' => $partner->id]);
    }

    public function pollMessages(Request $request, User $partner): JsonResponse
    {
        $me = $request->user();
        $afterId = (int) $request->query('after', 0);

        Message::where('sender_id', $partner->id)
            ->where('receiver_id', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $newMessages = Message::where('id', '>', $afterId)
            ->where(function ($q) use ($me, $partner) {
                $q->where(function ($sub) use ($me, $partner) {
                    $sub->where('sender_id', $me->id)->where('receiver_id', $partner->id)->where('deleted_by_sender', false);
                })->orWhere(function ($sub) use ($me, $partner) {
                    $sub->where('sender_id', $partner->id)->where('receiver_id', $me->id)->where('deleted_by_receiver', false);
                });
            })
            ->orderBy('id')
            ->get()
            ->map(fn (Message $m) => [
                'id' => $m->id,
                'is_me' => $m->sender_id === $me->id,
                'body' => $m->message,
                'image_url' => $m->image_path ? asset($m->image_path) : null,
                'time' => $m->created_at?->format('g:i A') ?? '',
                'is_read' => $m->is_read,
            ]);

        return response()->json([
            'messages' => $newMessages,
            'partner_online' => (bool) $partner->is_online,
            'partner_status' => $partner->is_online ? 'Online now' : 'Last seen ' . ($partner->last_active?->diffForHumans() ?? 'recently'),
        ]);
    }

    public function deleteConversation(Request $request, User $partner): RedirectResponse
    {
        $me = $request->user();
        Message::where('sender_id', $me->id)->where('receiver_id', $partner->id)->update(['deleted_by_sender' => true]);
        Message::where('sender_id', $partner->id)->where('receiver_id', $me->id)->update(['deleted_by_receiver' => true]);

        return redirect()->route('app.messages')->with('success', 'Conversation cleared from your inbox.');
    }

    public function blockUser(Request $request, User $user): RedirectResponse
    {
        UserBlock::firstOrCreate([
            'blocker_id' => $request->user()->id,
            'blocked_id' => $user->id,
        ]);

        return redirect()->route('app.discover')->with('success', "{$user->firstName()} has been blocked.");
    }

    public function reportUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        UserReport::create([
            'reporter_id' => $request->user()->id,
            'reported_id' => $user->id,
            'reason' => $data['reason'],
            'content_snapshot' => $data['details'] ?? null,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Report submitted. Our safety team reviews every report promptly.');
    }

    public function coffeeDates(Request $request): View
    {
        $me = $request->user();

        $dates = CoffeeDate::where('requester_id', $me->id)
            ->orWhere('partner_id', $me->id)
            ->with(['requester', 'partner'])
            ->latest()
            ->get();

        $candidates = User::where('id', '!=', $me->id)
            ->where('status', 'active')
            ->where('is_admin', false)
            ->orderBy('full_name')
            ->take(100)
            ->get();

        return view('app.coffee-dates', [
            'me' => $me,
            'dates' => $dates,
            'candidates' => $candidates,
            'cities' => City::where('is_active', true)->orderBy('sort_order')->get(),
            'datePlaces' => DatePlace::orderByDesc('rating')->get(),
        ]);
    }

    public function storeCoffeeDate(Request $request): RedirectResponse
    {
        $me = $request->user();
        $data = $request->validate([
            'partner_id' => ['required', 'integer', 'exists:users,id', 'different:requester_id'],
            'city' => ['required', 'string', 'max:120'],
            'venue_suggestion' => ['nullable', 'string', 'max:160'],
            'preferred_date' => ['required', 'string', 'max:120'],
            'meeting_preferences' => ['nullable', 'string', 'max:255'],
        ]);

        $coffeeDate = CoffeeDate::create([
            'requester_id' => $me->id,
            'partner_id' => $data['partner_id'],
            'city' => $data['city'],
            'venue_suggestion' => $data['venue_suggestion'] ?: 'Public daytime café',
            'preferred_date' => $data['preferred_date'],
            'meeting_preferences' => $data['meeting_preferences'] ?: 'Daytime coffee & conversation',
            'status' => 'pending',
        ]);

        UserNotification::create([
            'user_id' => $data['partner_id'],
            'from_user_id' => $me->id,
            'type' => 'coffee_date',
            'title' => 'New Coffee Date Invitation',
            'content' => "{$me->firstName()} suggested coffee in {$coffeeDate->city} ({$coffeeDate->preferred_date}).",
            'is_read' => false,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Coffee date invitation sent!');
    }

    public function respondCoffeeDate(Request $request, CoffeeDate $coffeeDate): RedirectResponse
    {
        $me = $request->user();
        abort_unless($coffeeDate->partner_id === $me->id || $coffeeDate->requester_id === $me->id, 403);

        $data = $request->validate(['status' => ['required', 'in:accepted,rejected,cancelled']]);
        $coffeeDate->update(['status' => $data['status']]);

        $otherId = $coffeeDate->requester_id === $me->id ? $coffeeDate->partner_id : $coffeeDate->requester_id;
        UserNotification::create([
            'user_id' => $otherId,
            'from_user_id' => $me->id,
            'type' => 'coffee_date',
            'title' => 'Coffee Date Update',
            'content' => "{$me->firstName()} marked your coffee date as {$data['status']}.",
            'is_read' => false,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Coffee date status updated.');
    }

    public function notifications(Request $request): View
    {
        $me = $request->user();
        $notifications = UserNotification::where('user_id', $me->id)->latest('created_at')->paginate(20);

        return view('app.notifications', [
            'me' => $me,
            'notifications' => $notifications,
        ]);
    }

    public function markNotificationsRead(Request $request): RedirectResponse
    {
        UserNotification::where('user_id', $request->user()->id)->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    }

    public function profile(Request $request): View
    {
        return view('app.profile', [
            'me' => $request->user(),
            'cities' => City::where('is_active', true)->orderBy('sort_order')->get(),
            'interestOptions' => InterestCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $me = $request->user();

        $data = $request->validate([
            'full_name' => ['required', 'string', 'min:2', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'dob' => ['nullable', 'date', 'before:-18 years'],
            'gender' => ['required', 'in:male,female,nonbinary,other'],
            'preference' => ['required', 'in:male,female,everyone'],
            'coffee_preference' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:600'],
            'interests' => ['nullable', 'array'],
            'hobbies' => ['nullable', 'string', 'max:300'],
            'profile_visible' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'max:3072'],
        ]);

        $avatarPath = $me->avatar;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $dir = public_path('uploads/avatars');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = 'avatar_' . $me->id . '_' . Str::random(10) . '.' . SqlSafe::safeImageExtension($file);
            $file->move($dir, $filename);
            $avatarPath = 'uploads/avatars/' . $filename;
        }

        $hobbiesArr = array_values(array_filter(array_map('trim', explode(',', (string) ($data['hobbies'] ?? '')))));

        $me->update([
            'full_name' => $data['full_name'],
            'city' => $data['city'],
            'dob' => $data['dob'] ?? $me->dob,
            'gender' => $data['gender'],
            'preference' => $data['preference'],
            'coffee_preference' => $data['coffee_preference'] ?? $me->coffee_preference,
            'bio' => $data['bio'] ?? $me->bio,
            'interests' => json_encode($data['interests'] ?? []),
            'hobbies' => json_encode($hobbiesArr),
            'profile_visible' => $request->boolean('profile_visible'),
            'avatar' => $avatarPath,
        ]);

        return back()->with('success', 'Your CupDate profile has been updated.');
    }

    public function requestVerification(Request $request): RedirectResponse
    {
        $me = $request->user();
        $me->update(['verification_status' => 'pending']);

        return back()->with('success', 'Verification request submitted for moderator review.');
    }

    public function upgradePremium(Request $request): RedirectResponse
    {
        $me = $request->user();
        $me->update([
            'is_premium' => ! $me->is_premium,
            'premium_until' => ! $me->is_premium ? now()->addMonth() : null,
        ]);

        return back()->with('success', $me->is_premium ? 'CupDate Plus supporter badge updated!' : 'Switched back to free plan.');
    }

    public function deleteAccount(Request $request): RedirectResponse
    {
        $me = $request->user();
        $me->update([
            'profile_visible' => false,
            'is_online' => false,
            'status' => 'suspended',
        ]);
        $me->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Your account has been removed from CupDate.');
    }
}
