@extends('layouts.app')

@section('title', 'Travel Groups & Coffee Road Trip Meetups in India | CupDate')
@section('description', 'Find verified travel companions and weekend coffee travel groups for Himachal Pradesh, Spiti, Dharamshala, Manali, Ladakh, Goa, Rishikesh, and Jaipur.')

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        {
            "@@type": "Question",
            "name": "How do travel groups work on CupDate?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Browse upcoming regional travel routes, filter by destination or pace, connect with verified members over chat and CupTV video first, and meet at safe daytime cafés before traveling."
            }
        }
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero section-cream">
    <div class="container">
        <span class="eyebrow-pill"><x-icon name="map-pin" class="cup-icon-xs" /> GROUP TRAVEL &amp; ROAD TRIPS</span>
        <h1 class="page-title left mt-12">Travel Groups &amp; Weekend Coffee Meetups</h1>
        <p class="page-subtitle left">Explore upcoming mountain, heritage, and coastal group trip themes across India. Connect over chat and CupTV video first, and always begin with a public coffee meetup.</p>

        <form method="GET" action="{{ route('travel.groups') }}" class="discover-filter-form mt-20">
            <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search destination (e.g. Spiti, Goa, Manali, Jaipur)...">
            <select name="region">
                <option value="">All Regions</option>
                @foreach (['Himachal & Spiti', 'Uttarakhand', 'Ladakh & Kashmir', 'Rajasthan Heritage', 'Coastal & South India'] as $reg)
                    <option value="{{ $reg }}" @selected(($activeRegion ?? '') === $reg)>{{ $reg }}</option>
                @endforeach
            </select>
            <select name="style">
                <option value="">All Trip Styles</option>
                @foreach (['Cafe & Workation', 'Weekend Road Trip', 'Trekking & Hills', 'Heritage & Food'] as $sty)
                    <option value="{{ $sty }}" @selected(($activeStyle ?? '') === $sty)>{{ $sty }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-pink btn-sm">Filter Trips</button>
        </form>
    </div>
</section>

<section class="section-white">
    <div class="container">
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

            <div class="cards-grid-3" data-skeleton-content>
                @forelse ($trips as $trip)
                    <article class="profile-card-public">
                        <div class="profile-card-img-wrap">
                            <img src="{{ asset($trip['image']) }}" alt="{{ $trip['title'] }}" loading="lazy">
                            <span class="verified-pill">{{ $trip['style'] }}</span>
                        </div>
                        <div class="profile-card-body">
                            <div class="profile-card-top">
                                <h3>{{ $trip['title'] }}</h3>
                            </div>
                            <span class="city-tag"><x-icon name="map-pin" class="cup-icon-xs" /> {{ $trip['region'] }} · {{ $trip['duration'] }}</span>
                            <p class="profile-card-bio">{{ $trip['summary'] }}</p>
                            <div class="tag-list">
                                <span class="tag-soft">{{ $trip['meetup_spot'] }}</span>
                            </div>
                            <a href="{{ auth()->check() ? route('app.discover', ['q' => $trip['city_match']]) : route('register') }}" class="btn btn-pink btn-full btn-sm mt-8">
                                {{ auth()->check() ? 'Find Travelers in ' . $trip['city_match'] : 'Join Free to Connect' }}
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="empty-state-card">
                        <h3>No travel groups matched that search</h3>
                        <p class="muted-small">Clear filters to browse all curated India travel meetup itineraries.</p>
                        <a href="{{ route('travel.groups') }}" class="btn btn-outline btn-sm mt-8">Show All Trips</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="honest-story-card mt-24">
            <h2 class="card-title">Safe Group Travel Guidelines</h2>
            <p class="mt-8">Always introduce yourselves via 1-to-1 chat and a brief CupTV or private video call first, meet initially in a sunlit neighborhood café before booking shared travel, and inform a trusted friend of your itinerary.</p>
            <div class="hero-actions">
                <a href="{{ route('games.truth-dare') }}" class="btn btn-coffee btn-sm">Play Road-Trip Truth or Dare</a>
                <a href="{{ route('seo.solo-travel') }}" class="btn btn-outline btn-sm">Solo Travel Safety Guide</a>
            </div>
        </div>
    </div>
</section>
@endsection
