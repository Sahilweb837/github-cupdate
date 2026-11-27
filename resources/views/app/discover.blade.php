@extends('layouts.dashboard')

@section('title', 'Discover | CupDate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">DISCOVER PEOPLE</span>
        <h1 class="app-page-title">Good to see you, {{ $me->firstName() }}</h1>
    </div>
    <a href="{{ route('app.notifications') }}" class="app-status-pill">
        <x-icon name="bell" class="cup-icon-xs" />
        <span>{{ $unreadNotifications }} unread</span>
    </a>
</div>

<form method="GET" action="{{ route('app.discover') }}" class="discover-filter-form mb-20">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search name, city, or bio...">
    <select name="city">
        <option value="">All Cities</option>
        @foreach ($cities as $city)
            <option value="{{ $city->name }}" @selected(request('city') === $city->name)>{{ $city->name }}</option>
        @endforeach
    </select>
    <select name="interest">
        <option value="">All Interests</option>
        @foreach ($interests as $interest)
            <option value="{{ $interest->name }}" @selected(request('interest') === $interest->name)>{{ $interest->name }}</option>
        @endforeach
    </select>
    <select name="sort">
        <option value="nearby" @selected(request('sort', 'nearby') === 'nearby')>Nearby</option>
        <option value="new" @selected(request('sort') === 'new')>New Members</option>
        <option value="popular" @selected(request('sort') === 'popular')>Popular</option>
        <option value="random" @selected(request('sort') === 'random')>Random</option>
    </select>
    <button type="submit" class="btn btn-pink btn-sm">Filter</button>
</form>

<div class="skeleton-grid-wrap" data-skeleton-container>
    <div class="skeleton-overlay" data-skeleton-loader aria-hidden="true">
        @for ($i = 0; $i < 3; $i++)
            <div class="skeleton-card">
                <div class="skeleton-box skeleton-img"></div>
                <div class="skeleton-box skeleton-line w-60"></div>
                <div class="skeleton-box skeleton-line w-85"></div>
            </div>
        @endfor
    </div>

    <div class="profiles-grid" data-skeleton-content>
        @forelse ($profiles as $person)
            @php $acted = $mySwipes[$person->id] ?? null; @endphp
            <article class="profile-card-public" data-discover-card="{{ $person->id }}">
                <div class="profile-card-img-wrap">
                    <x-avatar :user="$person" :alt="$person->firstName()" />
                    @php $sharedCount = count(array_intersect($me->interestsList(), $person->interestsList())); @endphp
                    @if ($sharedCount > 0)
                        <span class="compat-badge">{{ $sharedCount }} shared {{ Str::plural('interest', $sharedCount) }}</span>
                    @endif
                    @if ($person->is_online)
                        <span class="online-pill"><span class="online-dot"></span> Online</span>
                    @endif
                </div>

                <div class="profile-card-body">
                    <div class="profile-card-top">
                        <h3>{{ $person->firstName() }}@if($person->ageNumber()), {{ $person->ageNumber() }}@endif</h3>
                        <span class="city-tag"><x-icon name="map-pin" class="cup-icon-xs" /> {{ $person->city }}</span>
                    </div>
                    @if ($person->bio)
                        <p class="profile-card-bio">{{ Str::limit($person->bio, 90) }}</p>
                    @endif

                    <div class="tag-list">
                        @foreach (array_slice($person->interestsList(), 0, 3) as $tag)
                            <span class="tag-soft">{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="discover-card-actions">
                        <button type="button" class="btn btn-outline btn-xs" data-swipe-btn data-target="{{ $person->id }}" data-action="dislike" @disabled($acted === 'dislike')>
                            {{ $acted === 'dislike' ? 'Passed' : 'Pass' }}
                        </button>
                        <button type="button" class="btn btn-pink btn-xs" data-swipe-btn data-target="{{ $person->id }}" data-action="request" @disabled($acted && $acted !== 'dislike')>
                            {{ $acted && $acted !== 'dislike' ? 'Requested' : 'Send Request' }}
                        </button>
                        <a href="{{ route('app.messages', ['with' => $person->id]) }}" class="btn btn-blush btn-xs">Chat</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty-state-card">
                <h3>No members matched this filter</h3>
                <p>Try clearing your filters to explore all visible profiles.</p>
                <a href="{{ route('app.discover') }}" class="btn btn-pink btn-sm">Show All</a>
            </div>
        @endforelse
    </div>
</div>

<div class="pagination-wrap">{{ $profiles->links() }}</div>
@endsection
