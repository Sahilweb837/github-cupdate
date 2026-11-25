@extends('layouts.admin')

@section('title', 'Moderation & Support — CupDate Admin')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">SAFETY &amp; SUPPORT</span>
        <h1 class="app-page-title">Moderation &amp; Tickets</h1>
    </div>
</div>

<div class="admin-two-col">
    <section class="admin-panel-card">
        <h2 class="card-title">User Reports</h2>
        <div class="stack-sm mt-16">
            @forelse ($reports as $report)
                <div class="moderation-item">
                    <div>
                        <strong>Reported: {{ $report->reportedUser?->full_name ?? 'User #' . $report->reported_id }}</strong>
                        <div class="muted-small">By {{ $report->reporter?->full_name ?? 'Member' }} · {{ $report->reason }}</div>
                        @if ($report->content_snapshot)
                            <p class="muted-small">{{ $report->content_snapshot }}</p>
                        @endif
                    </div>
                    <div class="admin-action-btns">
                        <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                            @csrf <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="btn btn-pink btn-xs">Resolve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                            @csrf <input type="hidden" name="status" value="dismissed">
                            <button type="submit" class="btn btn-outline btn-xs">Dismiss</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="muted-small">No user reports to review.</p>
            @endforelse
        </div>
    </section>

    <section class="admin-panel-card">
        <h2 class="card-title">Messages Moderation</h2>
        <div class="stack-sm mt-16">
            @forelse ($messages as $msg)
                <div class="moderation-item">
                    <div>
                        <strong>{{ $msg->sender?->firstName() }} → {{ $msg->receiver?->firstName() }}</strong>
                        <p class="muted-small">{{ Str::limit($msg->message ?: '[Photo attachment]', 120) }}</p>
                    </div>
                    <div class="admin-action-btns">
                        <form method="POST" action="{{ route('admin.messages.moderate', $msg) }}">
                            @csrf <input type="hidden" name="action" value="flag">
                            <button type="submit" class="btn btn-outline btn-xs">{{ $msg->is_flagged ? 'Unflag' : 'Flag' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.messages.moderate', $msg) }}">
                            @csrf <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-outline btn-xs">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="muted-small">No messages logged yet.</p>
            @endforelse
        </div>
    </section>
</div>

<section class="admin-panel-card mt-24">
    <h2 class="card-title">Coffee Moments Moderation (Anti-Nudity &amp; Anti-Spam)</h2>
    <div class="stack-sm mt-16">
        @forelse ($moments ?? [] as $moment)
            <div class="moderation-item">
                <div>
                    <strong>{{ $moment->user?->full_name ?? 'Member' }} · {{ $moment->city ?? 'India' }}</strong>
                    <div class="muted-small">
                        Status: {{ ($moment->is_approved ?? true) ? 'Approved' : 'Pending Review' }}
                        @if ($moment->is_flagged ?? false) · <strong>Flagged</strong> @endif
                    </div>
                    <p class="muted-small">{{ Str::limit($moment->content, 140) }}</p>
                </div>
                <div class="admin-action-btns">
                    <form method="POST" action="{{ route('admin.moments.moderate', $moment) }}">
                        @csrf <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-pink btn-xs">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.moments.moderate', $moment) }}">
                        @csrf <input type="hidden" name="action" value="flag">
                        <button type="submit" class="btn btn-outline btn-xs">{{ ($moment->is_flagged ?? false) ? 'Unflag' : 'Flag' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.moments.moderate', $moment) }}">
                        @csrf <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-outline btn-xs">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="muted-small">No moments posted yet.</p>
        @endforelse
    </div>
</section>

<section class="admin-panel-card mt-24">
    <h2 class="card-title">Café Reviews Moderation</h2>
    <div class="stack-sm mt-16">
        @forelse ($cafeReviews ?? [] as $rev)
            <div class="moderation-item">
                <div>
                    <strong>{{ $rev->cafe_name }} ({{ $rev->city }}) · ★ {{ $rev->rating }}/5</strong>
                    <div class="muted-small">By {{ $rev->user?->full_name ?? 'Member' }} · {{ $rev->safety_tag }}</div>
                    <p class="muted-small">{{ Str::limit($rev->review_text, 140) }}</p>
                </div>
                <div class="admin-action-btns">
                    <form method="POST" action="{{ route('admin.cafes.moderate', $rev) }}">
                        @csrf <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-pink btn-xs">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.cafes.moderate', $rev) }}">
                        @csrf <input type="hidden" name="action" value="flag">
                        <button type="submit" class="btn btn-outline btn-xs">{{ $rev->is_flagged ? 'Unflag' : 'Flag' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.cafes.moderate', $rev) }}">
                        @csrf <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-outline btn-xs">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="muted-small">No café reviews yet.</p>
        @endforelse
    </div>
</section>

<section class="admin-panel-card mt-24">
    <h2 class="card-title">Support Tickets &amp; Contact Inquiries</h2>
    <div class="stack-sm mt-16">
        @forelse ($tickets as $ticket)
            <div class="moderation-item">
                <div>
                    <strong>{{ $ticket->subject }}</strong>
                    <div class="muted-small">{{ $ticket->name }} ({{ $ticket->email }}) · Status: {{ $ticket->status }}</div>
                    <p class="muted-small">{{ $ticket->message }}</p>
                </div>
                <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" class="ticket-reply-form">
                    @csrf
                    <input type="text" name="admin_reply" value="{{ $ticket->admin_reply }}" placeholder="Admin response...">
                    <select name="status">
                        <option value="open" @selected($ticket->status === 'open')>Open</option>
                        <option value="in_progress" @selected($ticket->status === 'in_progress')>In Progress</option>
                        <option value="resolved" @selected($ticket->status === 'resolved')>Resolved</option>
                    </select>
                    <button type="submit" class="btn btn-pink btn-xs">Save</button>
                </form>
            </div>
        @empty
            <p class="muted-small">No support tickets yet.</p>
        @endforelse
    </div>
</section>
@endsection
