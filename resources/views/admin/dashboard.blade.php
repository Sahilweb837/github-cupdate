@extends('layouts.admin')

@section('title', 'Overview — CupDate Admin')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">PLATFORM METRICS (LIVE MYSQL)</span>
        <h1 class="app-page-title">Admin Overview</h1>
    </div>
    <a href="{{ route('admin.users') }}" class="btn btn-pink btn-sm">Manage Users</a>
</div>

<div class="admin-stats-grid">
    <div class="admin-stat-card"><span>Live Visitors Now</span><strong data-live-visitors>{{ $stats['active_visitors'] ?? 0 }}</strong></div>
    <div class="admin-stat-card"><span>Members Online</span><strong data-live-members>{{ $stats['online_members'] ?? 0 }}</strong></div>
    <div class="admin-stat-card"><span>Live Video Queue</span><strong>{{ $stats['live_video_active'] ?? 0 }}</strong></div>
    <div class="admin-stat-card"><span>Total Users</span><strong>{{ $stats['total_users'] }}</strong></div>
    <div class="admin-stat-card"><span>Active Users</span><strong>{{ $stats['active_users'] }}</strong></div>
    <div class="admin-stat-card"><span>New (7 days)</span><strong>{{ $stats['new_users'] }}</strong></div>
    <div class="admin-stat-card"><span>Verified</span><strong>{{ $stats['verified_users'] }}</strong></div>
    <div class="admin-stat-card"><span>Pending Reports</span><strong>{{ $stats['reported_accounts'] }}</strong></div>
    <div class="admin-stat-card"><span>Suspended</span><strong>{{ $stats['suspended_accounts'] }}</strong></div>
    <div class="admin-stat-card"><span>Deleted</span><strong>{{ $stats['deleted_accounts'] }}</strong></div>
    <div class="admin-stat-card"><span>Coffee Dates</span><strong>{{ $stats['coffee_dates'] }}</strong></div>
    <div class="admin-stat-card"><span>Open Tickets</span><strong>{{ $stats['open_tickets'] }}</strong></div>
</div>

<div class="admin-two-col mt-24">
    <section class="admin-panel-card">
        <div class="panel-head-row">
            <h2 class="card-title">Recent Registrations</h2>
            <a href="{{ route('admin.users') }}" class="text-link">All users →</a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Verified</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentUsers as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->full_name }}</strong>
                                <div class="muted-small">{{ $user->email }}</div>
                            </td>
                            <td>{{ $user->city }}</td>
                            <td><span class="status-pill status-{{ $user->deleted_at ? 'deleted' : $user->status }}">{{ $user->deleted_at ? 'Deleted' : ucfirst($user->status) }}</span></td>
                            <td>{{ $user->is_verified ? 'Verified ✓' : 'Unverified' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-panel-card">
        <div class="panel-head-row">
            <h2 class="card-title">Recent User Reports</h2>
            <a href="{{ route('admin.moderation') }}" class="text-link">Moderation →</a>
        </div>
        @forelse ($recentReports as $report)
            <div class="report-mini-row">
                <div>
                    <strong>{{ $report->reportedUser?->full_name ?? 'User #' . $report->reported_id }}</strong>
                    <div class="muted-small">{{ $report->reason }}</div>
                </div>
                <span class="status-pill">{{ ucfirst($report->status) }}</span>
            </div>
        @empty
            <p class="muted-small mt-12">No pending safety reports.</p>
        @endforelse
    </section>
</div>
@endsection
