@extends('layouts.app')

@section('title', 'Discover Genuine People | CupDate')
@section('description', 'Explore verified profiles across Shimla, Dharamshala, Kangra, Mandi, and Himachal Pradesh on CupDate.')

@section('content')
<section class="page-hero section-cream">
    <div class="container">
        <span class="eyebrow-text">DISCOVER</span>
        <h1 class="page-title left">Meet genuine people across Himachal Pradesh</h1>
        <p class="page-subtitle left">Filter by city, shared interests, or activity. Create a free account to send requests, chat, or video call.</p>

        <form method="GET" action="{{ route('public.discover') }}" class="discover-filter-form">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name, city, or bio...">
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
                <option value="random" @selected(request('sort') === 'random')>Random Discovery</option>
            </select>
            <button type="submit" class="btn btn-pink btn-sm">Apply Filters</button>
        </form>
    </div>
</section>

<section class="section-white">
    <div class="container">
        @if (session('info'))
            <div class="alert-info">{{ session('info') }}</div>
        @endif

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
                <article class="profile-card-public">
                    <div class="profile-card-img-wrap">
                        <x-avatar :user="$person" :alt="$person->firstName() . ' in ' . $person->city" />
                        @if ($person->is_online)
                            <span class="online-pill"><span class="online-dot"></span> Online</span>
                        @endif
                        @if ($person->is_verified)
                            <span class="verified-pill"><x-icon name="check" class="cup-icon-xs" /> Verified</span>
                        @endif
                    </div>
                    <div class="profile-card-body">
                        <div class="profile-card-top">
                            <h3>{{ $person->firstName() }}@if($person->ageNumber()), {{ $person->ageNumber() }}@endif</h3>
                            <span class="city-tag"><x-icon name="map-pin" class="cup-icon-xs" /> {{ $person->city }}</span>
                        </div>
                        @if ($person->bio)
                            <p class="profile-card-bio">{{ Str::limit($person->bio, 95) }}</p>
                        @endif
                        <div class="tag-list">
                            @foreach (array_slice($person->interestsList(), 0, 3) as $tag)
                                <span class="tag-soft">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <a href="{{ auth()->check() ? route('app.discover', ['q' => $person->firstName()]) : route('register') }}" class="btn btn-pink btn-full btn-sm">
                            Send Request
                        </a>
                    </div>
                </article>
            @empty
                <div class="empty-state-card">
                    <h3>Explore Verified Connections Across India</h3>
                    <p>Create your free CupDate profile to appear in city discovery, join CupTV live video, and connect over daytime coffee dates.</p>
                    <div class="hero-actions mt-12">
                        <a href="{{ route('register') }}" class="btn btn-pink btn-sm">Create Free Account</a>
                        <a href="{{ route('cities') }}" class="btn btn-outline btn-sm">Explore 100+ Cities</a>
                        <a href="{{ route('live-video.index') }}" class="btn btn-coffee btn-sm">Open CupTV Live</a>
                    </div>
                </div>
            @endforelse
            </div>
        </div>

        <div class="pagination-wrap">{{ $profiles->links() }}</div>
    </div>
</section>
@endsection
