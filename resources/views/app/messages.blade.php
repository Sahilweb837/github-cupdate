@extends('layouts.dashboard')

@section('title', 'Messages — Cupdate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">CONVERSATIONS</span>
        <h1 class="app-page-title">Messages</h1>
    </div>
</div>

<div class="messages-shell" data-messages-app>
    <div class="threads-pane">
        @foreach ($threads as $idx => $thread)
            <button type="button"
                class="thread-item {{ $idx === 0 ? 'is-active' : '' }}"
                data-thread-switch="{{ $thread['id'] }}">
                <img src="{{ asset($thread['avatar']) }}" alt="{{ $thread['name'] }}" class="thread-avatar">
                <div class="thread-meta">
                    <div class="thread-name">{{ $thread['name'] }}</div>
                    <div class="thread-preview">{{ $thread['preview'] }}</div>
                </div>
            </button>
        @endforeach
    </div>

    <div class="chat-pane">
        @foreach ($threads as $idx => $thread)
            <div class="chat-thread-panel {{ $idx === 0 ? 'is-active' : '' }}" data-thread-panel="{{ $thread['id'] }}">
                <div class="chat-pane-header">
                    <img src="{{ asset($thread['avatar']) }}" alt="{{ $thread['name'] }}" class="thread-avatar">
                    <div>
                        <div class="thread-name">{{ $thread['name'] }}</div>
                        <div class="chat-status">{{ $thread['status'] }}</div>
                    </div>
                    <a href="{{ route('app.dates') }}" class="btn btn-outline btn-xs push-right">Invite to coffee</a>
                </div>

                <div class="chat-messages-scroll" data-chat-feed>
                    @foreach ($thread['messages'] as $msg)
                        <div class="chat-msg {{ $msg['from'] === 'me' ? 'is-me' : 'is-them' }}">
                            <div class="chat-msg-bubble">{{ $msg['text'] }}</div>
                            <span class="chat-msg-time">{{ $msg['time'] }}</span>
                        </div>
                    @endforeach
                </div>

                <form class="chat-composer" data-chat-form>
                    <input type="text" placeholder="Message {{ $thread['name'] }} about coffee..." required>
                    <button type="submit" class="btn btn-coral btn-sm">Send</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
