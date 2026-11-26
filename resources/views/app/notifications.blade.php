@extends('layouts.dashboard')

@section('title', 'Notifications | CupDate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">ACTIVITY</span>
        <h1 class="app-page-title">Notifications</h1>
    </div>
    <form method="POST" action="{{ route('app.notifications.read') }}">
        @csrf
        <button type="submit" class="btn btn-outline btn-sm">Mark all as read</button>
    </form>
</div>

<div class="stack-sm">
    @forelse ($notifications as $note)
        <div class="notification-row {{ $note->is_read ? '' : 'is-unread' }}">
            <div class="notif-icon"><x-icon name="bell" class="cup-icon-sm" /></div>
            <div class="notif-body">
                <strong>{{ $note->title }}</strong>
                <p>{{ $note->content }}</p>
            </div>
            <span class="muted-small">{{ $note->created_at?->diffForHumans() }}</span>
        </div>
    @empty
        <div class="empty-state-card">
            <h3>You’re all caught up</h3>
            <p>New requests, matches, messages, and coffee date updates appear here.</p>
        </div>
    @endforelse
</div>

<div class="pagination-wrap">{{ $notifications->links() }}</div>
@endsection
