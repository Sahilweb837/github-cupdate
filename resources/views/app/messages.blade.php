@extends('layouts.dashboard')

@section('title', 'Messages — CupDate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">1-TO-1 CHAT</span>
        <h1 class="app-page-title">Messages</h1>
    </div>
</div>

<div class="messages-shell">
    <aside class="threads-pane">
        @forelse ($partners as $partner)
            <a href="{{ route('app.messages', ['with' => $partner->id]) }}"
               class="thread-item {{ $activePartner && $activePartner->id === $partner->id ? 'is-active' : '' }}">
                <img src="{{ $partner->avatarUrl() }}" alt="{{ $partner->firstName() }}" class="thread-avatar">
                <div class="thread-meta">
                    <div class="thread-name">{{ $partner->firstName() }}</div>
                    <div class="thread-preview">
                        {{ $partner->is_online ? 'Online · ' : '' }}{{ $partner->city }}
                    </div>
                </div>
            </a>
        @empty
            <p class="muted-small p-12">No conversations yet. Visit Discover to connect with someone!</p>
        @endforelse
    </aside>

    <section class="chat-pane">
        @if ($activePartner)
            <div class="chat-pane-header">
                <img src="{{ $activePartner->avatarUrl() }}" alt="{{ $activePartner->firstName() }}" class="thread-avatar">
                <div>
                    <div class="thread-name">{{ $activePartner->firstName() }} · {{ $activePartner->city }}</div>
                    <div class="chat-status" id="partnerOnlineStatus">
                        {{ $activePartner->is_online ? 'Online now' : 'Last seen ' . ($activePartner->last_active?->diffForHumans() ?? 'recently') }}
                    </div>
                </div>

                <div class="chat-header-tools">
                    <a href="{{ route('app.video.index', ['with' => $activePartner->id]) }}" class="btn btn-pink btn-xs">
                        <x-icon name="video" class="cup-icon-xs" /> Video Call
                    </a>
                    <a href="{{ route('app.coffee-dates', ['partner' => $activePartner->id]) }}" class="btn btn-outline btn-xs">
                        <x-icon name="coffee" class="cup-icon-xs" /> Coffee Date
                    </a>
                    <form method="POST" action="{{ route('app.messages.delete', $activePartner) }}" class="inline-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline btn-xs" onclick="return confirm('Clear this conversation from your inbox?')">Clear</button>
                    </form>
                    <form method="POST" action="{{ route('app.block', $activePartner) }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-xs" onclick="return confirm('Block this user?')">Block</button>
                    </form>
                </div>
            </div>

            <div class="chat-messages-scroll"
                 id="liveChatFeed"
                 data-poll-url="{{ route('app.messages.poll', $activePartner) }}"
                 data-last-id="{{ $thread->last()?->id ?? 0 }}">
                @forelse ($thread as $msg)
                    <div class="chat-msg {{ $msg->sender_id === $me->id ? 'is-me' : 'is-them' }}">
                        <div class="chat-msg-bubble">
                            @if ($msg->image_path)
                                <img src="{{ asset($msg->image_path) }}" alt="Shared photo" class="chat-shared-img">
                            @endif
                            @if ($msg->message)
                                <p>{{ $msg->message }}</p>
                            @endif
                        </div>
                        <span class="chat-msg-time">
                            {{ $msg->created_at?->format('g:i A') }}
                            @if ($msg->sender_id === $me->id)
                                · {{ $msg->is_read ? 'Read ✓✓' : 'Sent ✓' }}
                            @endif
                        </span>
                    </div>
                @empty
                    <div class="chat-empty-note">Say hello to {{ $activePartner->firstName() }} and suggest a coffee chat!</div>
                @endforelse
            </div>

            <form method="POST" action="{{ route('app.messages.send', $activePartner) }}" enctype="multipart/form-data" class="chat-composer" id="liveChatForm">
                @csrf
                <label class="attach-btn" title="Attach moderated image">
                    <input type="file" name="image" accept="image/*" hidden>
                    <span>+ Photo</span>
                </label>
                <input type="text" name="message" id="chatTextInput" placeholder="Write a respectful message...">
                <button type="submit" class="btn btn-pink btn-sm">Send</button>
            </form>

            <details class="report-inline-box">
                <summary>Report {{ $activePartner->firstName() }} for safety review</summary>
                <form method="POST" action="{{ route('app.report', $activePartner) }}" class="cupdate-form mt-8">
                    @csrf
                    <div class="form-row-2">
                        <input type="text" name="reason" placeholder="Reason (e.g., spam, fake profile)" required>
                        <input type="text" name="details" placeholder="Optional details">
                    </div>
                    <button type="submit" class="btn btn-outline btn-xs">Submit Report</button>
                </form>
            </details>
        @else
            <div class="p-24">
                <h3>Select a conversation</h3>
                <p class="muted-small">Connect with members in Discover to begin messaging.</p>
            </div>
        @endif
    </section>
</div>
@endsection
