@extends('layouts.admin')

@section('title', 'Registered Users | CupDate Admin')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">USER MANAGEMENT</span>
        <h1 class="app-page-title">Registered Accounts</h1>
    </div>
</div>

<form method="GET" action="{{ route('admin.users') }}" class="discover-filter-form mb-20">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search name, email, or city...">
    <select name="status">
        <option value="">All Statuses</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="verified" @selected(request('status') === 'verified')>Verified</option>
        <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
        <option value="deleted" @selected(request('status') === 'deleted')>Deleted</option>
    </select>
    <button type="submit" class="btn btn-pink btn-sm">Filter</button>
</form>

<section class="admin-panel-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>City</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Verification</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="admin-user-cell">
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->full_name }}">
                                <div>
                                    <strong>{{ $user->full_name }}</strong>
                                    <div class="muted-small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->city }}</td>
                        <td>
                            @if (auth()->user()->hasAdminRole('super_admin'))
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()" class="compact-select">
                                        @foreach (['user', 'super_admin', 'moderator', 'support_admin', 'content_manager'] as $r)
                                            <option value="{{ $r }}" @selected(($user->role ?: 'user') === $r)>{{ $r }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                {{ $user->role ?: 'user' }}
                            @endif
                        </td>
                        <td>
                            <span class="status-pill status-{{ $user->deleted_at ? 'deleted' : $user->status }}">
                                {{ $user->deleted_at ? 'Deleted' : ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->is_verified ? 'Verified' : ucfirst($user->verification_status ?? 'unverified') }}</td>
                        <td>
                            <div class="admin-action-btns">
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="toggle_verify">
                                    <button type="submit" class="btn btn-outline btn-xs">{{ $user->is_verified ? 'Unverify' : 'Verify' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="toggle_suspend">
                                    <button type="submit" class="btn btn-outline btn-xs">{{ $user->status === 'active' ? 'Suspend' : 'Activate' }}</button>
                                </form>
                                @if ($user->deleted_at)
                                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="restore">
                                        <button type="submit" class="btn btn-blush btn-xs">Restore</button>
                                    </form>
                                @elseif ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" onsubmit="return confirm('Soft-delete this account?')">
                                        @csrf
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-outline btn-xs">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<div class="pagination-wrap">{{ $users->links() }}</div>
@endsection
