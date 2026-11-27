@extends('layouts.admin')

@section('title', 'Overview & Visitor Analytics | CupDate Admin')

@section('content')
<div class="app-header-row" data-admin-live-url="{{ route('admin.analytics.live') }}">
    <div>
        <span class="eyebrow-text">REAL-TIME ANALYTICS &amp; VISITOR LOCATIONS</span>
        <h1 class="app-page-title">Admin Dashboard</h1>
    </div>
    <div class="admin-action-btns">
        <span class="app-status-pill"><span class="online-dot"></span> Live Tracking Active</span>
        <a href="{{ route('admin.users') }}" class="btn btn-pink btn-sm">Manage Users</a>
        <a href="{{ route('admin.moderation') }}" class="btn btn-outline btn-sm">Moderation</a>
    </div>
</div>

<div class="filter-bar admin-range-bar" role="region" aria-label="Analytics Time Range">
    <a href="{{ route('admin.dashboard', ['range' => 'today']) }}" class="filter-chip {{ $range === 'today' ? 'is-active' : '' }}">Today</a>
    <a href="{{ route('admin.dashboard', ['range' => '7d']) }}" class="filter-chip {{ $range === '7d' ? 'is-active' : '' }}">Last 7 Days</a>
    <a href="{{ route('admin.dashboard', ['range' => '30d']) }}" class="filter-chip {{ $range === '30d' ? 'is-active' : '' }}">Last 30 Days</a>
    <a href="{{ route('admin.dashboard', ['range' => 'all']) }}" class="filter-chip {{ $range === 'all' ? 'is-active' : '' }}">All Time</a>
    <span class="muted-small admin-range-note">
        Showing {{ $analytics['range_visits'] }} visits ({{ $analytics['range_unique'] }} unique) · {{ $analytics['member_visits'] }} signed-in · {{ $analytics['guest_visits'] }} guests
    </span>
</div>

<div class="admin-stats-grid">
    <div class="admin-stat-card"><span>Live Visitors (5m)</span><strong data-admin-stat="active_visitors">{{ $stats['active_visitors'] }}</strong></div>
    <div class="admin-stat-card"><span>Today Page Visits</span><strong data-admin-stat="today_visits">{{ $stats['today_visits'] }}</strong><small class="muted-small">Yesterday: {{ $stats['yesterday_visits'] }}</small></div>
    <div class="admin-stat-card"><span>Today Unique Visitors</span><strong data-admin-stat="today_unique">{{ $stats['today_unique'] }}</strong></div>
    <div class="admin-stat-card"><span>Last 7 Days Visits</span><strong>{{ $stats['visits_7d'] }}</strong></div>
    <div class="admin-stat-card"><span>Last 30 Days Visits</span><strong>{{ $stats['visits_30d'] }}</strong></div>
    <div class="admin-stat-card"><span>All-Time Total Visits</span><strong data-admin-stat="total_visits">{{ $stats['total_visits'] }}</strong><small class="muted-small">Unique: <span data-admin-stat="total_unique">{{ $stats['total_unique'] }}</span></small></div>
    <div class="admin-stat-card"><span>Members Online</span><strong data-admin-stat="online_members">{{ $stats['online_members'] }}</strong></div>
    <div class="admin-stat-card"><span>Registered Users</span><strong>{{ $stats['total_users'] }}</strong><small class="muted-small">New (7d): {{ $stats['new_users'] }}</small></div>
    <div class="admin-stat-card"><span>Verified Users</span><strong>{{ $stats['verified_users'] }}</strong></div>
    <div class="admin-stat-card"><span>Messages Sent</span><strong>{{ $stats['messages_count'] }}</strong></div>
    <div class="admin-stat-card"><span>Coffee Dates</span><strong>{{ $stats['coffee_dates'] }}</strong></div>
    <div class="admin-stat-card"><span>Pending Reports / Tickets</span><strong>{{ $stats['reported_accounts'] }} / {{ $stats['open_tickets'] }}</strong></div>
</div>

<section class="admin-panel-card mt-24">
    <div class="panel-head-row">
        <h2 class="card-title">Daily Visits Trend (Last 7 Days)</h2>
        <span class="muted-small">Page visits vs unique visitor sessions</span>
    </div>
    @php
        $daily = $analytics['daily_series'] ?? collect();
        $maxDay = max(1, (int) $daily->max('visits'));
    @endphp
    <div class="admin-chart-bars mt-16">
        @foreach ($daily as $day)
            @php $pct = $day['visits'] > 0 ? max(8, (int) round(($day['visits'] / $maxDay) * 100)) : 4; @endphp
            <div class="admin-bar-col" title="{{ $day['label'] }}: {{ $day['visits'] }} visits ({{ $day['unique'] }} unique)">
                <span class="admin-bar-count">{{ $day['visits'] }}</span>
                <div class="admin-bar-track">
                    <div class="admin-bar-fill" style="height: {{ $pct }}%"></div>
                </div>
                <span class="admin-bar-label">{{ $day['label'] }}</span>
                <span class="muted-small">{{ $day['unique'] }} uniq</span>
            </div>
        @endforeach
    </div>
</section>

<div class="admin-two-col mt-24">
    <section class="admin-panel-card">
        <div class="panel-head-row">
            <h2 class="card-title">Where Visitors Come From (Locations)</h2>
            <span class="muted-small">Real geo &amp; session tracking</span>
        </div>
        <div class="table-responsive mt-12">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>City / Region</th>
                        <th>Country</th>
                        <th>Visits</th>
                        <th>Unique</th>
                    </tr>
                </thead>
                <tbody>
                    @php $maxLocVisits = max(1, (int) ($analytics['top_locations']->max('visits') ?? 1)); @endphp
                    @forelse ($analytics['top_locations'] as $loc)
                        <tr>
                            <td>
                                <strong>{{ $loc->city ?: 'Unknown' }}</strong>
                                <div class="muted-small">{{ $loc->region ?: '-' }}</div>
                                <div class="admin-progress-track">
                                    <div class="admin-progress-bar" style="width: {{ min(100, round(($loc->visits / $maxLocVisits) * 100)) }}%"></div>
                                </div>
                            </td>
                            <td>{{ $loc->country ?: 'Unknown' }}</td>
                            <td><strong>{{ $loc->visits }}</strong></td>
                            <td>{{ $loc->unique_visitors }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted-small">Visitor locations will populate automatically as traffic arrives.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-panel-card">
        <div class="panel-head-row">
            <h2 class="card-title">Registered Users by City</h2>
            <a href="{{ route('admin.users') }}" class="text-link">All users →</a>
        </div>
        <div class="table-responsive mt-12">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Members</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usersByLocation as $uLoc)
                        <tr>
                            <td><strong>{{ $uLoc->city ?: 'Unknown' }}</strong></td>
                            <td>{{ $uLoc->state ?: '-' }}</td>
                            <td>{{ $uLoc->country ?: 'IN' }}</td>
                            <td><span class="admin-badge-soft">{{ $uLoc->total }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted-small">No user location records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<section class="admin-panel-card mt-24">
    <div class="panel-head-row">
        <h2 class="card-title">Online Users Right Now by City</h2>
        <span class="muted-small">Active within last 10 minutes (real sessions &amp; member records)</span>
    </div>
    <div class="table-responsive mt-12">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Online Now</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($onlineUsersByCity as $onlineLoc)
                    <tr>
                        <td><strong>{{ $onlineLoc->city ?: 'Unknown' }}</strong></td>
                        <td>{{ $onlineLoc->state ?: '-' }}</td>
                        <td>{{ $onlineLoc->country ?: 'India' }}</td>
                        <td><span class="admin-badge-soft">{{ $onlineLoc->online_total }} online</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted-small">No members are currently online in the last 10 minutes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="admin-two-col mt-24">
    <section class="admin-panel-card">
        <h2 class="card-title">Top Visited Pages</h2>
        <div class="table-responsive mt-12">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Page Path</th>
                        <th>Visits</th>
                        <th>Unique</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($analytics['top_pages'] as $pageRow)
                        <tr>
                            <td><code>{{ $pageRow->path }}</code></td>
                            <td><strong>{{ $pageRow->visits }}</strong></td>
                            <td>{{ $pageRow->unique_visitors }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted-small">No page visits recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-panel-card">
        <h2 class="card-title">Traffic Sources &amp; Devices</h2>
        <div class="stack-sm mt-12">
            <div>
                <strong class="muted-small">Devices</strong>
                <div class="tag-list mt-8">
                    @forelse ($analytics['device_breakdown'] as $dev)
                        <span class="tag-soft">{{ $dev->device_type }}: {{ $dev->visits }}</span>
                    @empty
                        <span class="muted-small">No device data yet.</span>
                    @endforelse
                </div>
            </div>
            <div class="mt-12">
                <strong class="muted-small">Referrer Sources</strong>
                <div class="table-responsive mt-8">
                    <table class="admin-table">
                        <thead><tr><th>Source</th><th>Visits</th></tr></thead>
                        <tbody>
                            @forelse ($analytics['top_referrers'] as $ref)
                                <tr><td>{{ $ref->referrer ?: 'Direct' }}</td><td><strong>{{ $ref->visits }}</strong></td></tr>
                            @empty
                                <tr><td colspan="2" class="muted-small">No referrer data yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="admin-panel-card mt-24">
    <div class="panel-head-row">
        <h2 class="card-title">Recent Real-Time Visitors Log</h2>
        <span class="muted-small">Latest 20 visits</span>
    </div>
    <div class="table-responsive mt-12">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Location</th>
                    <th>Page</th>
                    <th>Device</th>
                    <th>Source</th>
                    <th>User / IP</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($analytics['recent_visitors'] as $visit)
                    <tr>
                        <td>
                            <strong>{{ $visit->city ?: 'Unknown' }}</strong>
                            <div class="muted-small">{{ $visit->region ? $visit->region . ', ' : '' }}{{ $visit->country ?: 'Unknown' }}</div>
                        </td>
                        <td><code>{{ $visit->path }}</code></td>
                        <td><span class="admin-badge-soft">{{ $visit->device_type }}</span></td>
                        <td>{{ $visit->referrer ?: 'Direct' }}</td>
                        <td>
                            @if ($visit->user)
                                <strong>{{ $visit->user->full_name }}</strong>
                                <div class="muted-small">{{ $visit->ip_address }}</div>
                            @else
                                <span class="muted-small">Guest ({{ $visit->ip_address ?: '-' }})</span>
                            @endif
                        </td>
                        <td class="muted-small">{{ $visit->created_at?->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted-small">No visits logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

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
                            <td>{{ $user->is_verified ? 'Verified' : 'Unverified' }}</td>
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
