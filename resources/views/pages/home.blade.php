@extends('layouts.app')

@section('title', \App\Models\SiteSetting::getValue('seo_default_title', 'CupDate | Free Coffee Dating and CupTV Live Video'))

@push('preload')
<link rel="preload" as="image" href="{{ asset('images/hero-coffee-couple.jpg') }}" fetchpriority="high">
<link rel="preload" as="image" href="{{ asset('images/couple-cafe-1.jpg') }}">
@endpush

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach ($faqs as $idx => $faq)
        {
            "@@type": "Question",
            "name": "{{ addslashes($faq['q']) }}",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "{{ addslashes($faq['a']) }}"
            }
        }{{ $loop->last ? '' : ',' }}
        @endforeach
    ]
}
</script>
@endpush

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero-coffee-couple.jpg'),
            'alt' => 'Couple talking over coffee inside a sunlit local cafe',
            'city' => 'Shimla',
            'caption' => 'Mall Road Cafe Meetup',
        ],
        [
            'image' => asset('images/couple-cafe-1.jpg'),
            'alt' => 'Indian couple smiling over cappuccinos beside a mountain cafe window',
            'city' => 'Dharamshala',
            'caption' => 'Morning Cappuccino in the Hills',
        ],
        [
            'image' => asset('images/cafe-blue-bottle.jpg'),
            'alt' => 'Warm sunlit neighborhood coffee shop table set for two',
            'city' => 'New Delhi',
            'caption' => 'Connaught Place Espresso Date',
        ],
        [
            'image' => asset('images/member-sofia.jpg'),
            'alt' => 'Woman smiling during a relaxed afternoon coffee meetup',
            'city' => 'Bengaluru',
            'caption' => 'Indiranagar Roastery',
        ],
        [
            'image' => asset('images/member-diego.jpg'),
            'alt' => 'Young man outdoors enjoying daytime coffee conversation',
            'city' => 'Chandigarh',
            'caption' => 'Sector 17 Courtyard Coffee',
        ],
    ];
@endphp
<section class="hero-section">
    <div class="hero-bg-slider" data-hero-bg-slider aria-hidden="true">
        @foreach ($heroSlides as $idx => $slide)
            <div class="hero-bg-slide {{ $idx === 0 ? 'is-active' : '' }}" data-bg-index="{{ $idx }}">
                <img src="{{ $slide['image'] }}" alt="" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}" decoding="async">
            </div>
        @endforeach
        <div class="hero-bg-overlay"></div>
    </div>

    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow-pill">
                <x-icon name="coffee" class="cup-icon-xs" />
                <span>100% FREE COFFEE DATES &amp; CUPTV LIVE VIDEO</span>
            </div>

            <h1 class="hero-title">{{ $headline }}</h1>
            <p class="hero-subtitle">{{ $subheadline }}</p>

            <div class="free-offer-banner">
                <div>
                    <strong>Free Forever Access ($0)</strong>
                    <span>Free Chat, CupTV Live Video &amp; Moments - no paid paywall</span>
                </div>
                <a href="{{ auth()->check() ? route('app.discover') : route('register') }}" class="btn btn-pink btn-sm">
                    {{ auth()->check() ? 'Open Free App' : 'Claim Free Access' }}
                </a>
            </div>

            <div class="hero-actions mt-16">
                <a href="{{ auth()->check() ? route('app.discover') : route('register') }}" class="btn btn-pink">
                    Join Now - 100% Free
                </a>
                <a href="{{ route('live-video.index') }}" class="btn btn-coffee">
                    <x-icon name="video" class="cup-icon-xs" /> Start CupTV Live Video
                </a>
                <a href="{{ route('moments.index') }}" class="btn btn-outline">
                    Share Moments
                </a>
            </div>

            <div class="trust-pills" aria-label="Platform Trust Highlights">
                <span><x-icon name="shield" class="cup-icon-xs" /> Anti-Nudity &amp; Anti-Spam</span>
                <span><x-icon name="message" class="cup-icon-xs" /> Free Chat</span>
                <span><x-icon name="video" class="cup-icon-xs" /> CupTV Free Video</span>
                <span><x-icon name="heart" class="cup-icon-xs" /> <strong data-live-visitors>{{ $liveStats['active_visitors'] }}</strong> Live Now</span>
            </div>
        </div>

        <div class="hero-visual">
            <div class="tinder-stage" data-tinder-deck>
                <div class="tinder-deck-cards" aria-live="polite">
                    @foreach ($heroSlides as $idx => $slide)
                        <article class="tinder-card" data-tinder-card data-slide-index="{{ $idx }}">
                            <img src="{{ $slide['image'] }}" alt="{{ $slide['alt'] }}" loading="{{ $idx < 2 ? 'eager' : 'lazy' }}" decoding="async" draggable="false">
                            <div class="tinder-card-scrim"></div>
                            <div class="tinder-card-stamp tinder-stamp-like" aria-hidden="true">COFFEE</div>
                            <div class="tinder-card-stamp tinder-stamp-pass" aria-hidden="true">NEXT</div>
                            <div class="tinder-card-caption">
                                <span class="tinder-card-city"><x-icon name="map-pin" class="cup-icon-xs" /> {{ $slide['city'] }}</span>
                                <strong class="tinder-card-title">{{ $slide['caption'] }}</strong>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="tinder-controls" aria-label="Photo deck controls">
                    <button type="button" class="btn btn-outline btn-sm tinder-nav-btn" data-deck-prev aria-label="Previous couple photo">Prev</button>
                    <span class="tinder-counter"><span data-deck-current>1</span> / {{ count($heroSlides) }}</span>
                    <button type="button" class="btn btn-pink btn-sm tinder-nav-btn" data-deck-next aria-label="Next couple photo">Swipe Next</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container">
        <div class="section-heading centered">
            <span class="eyebrow-text">BUILT FOR REAL CONNECTIONS</span>
            <h2 class="section-title">Everything free: live video, coffee dates &amp; moments</h2>
        </div>

        <div class="cards-grid-3">
            <article class="minimal-card">
                <div class="card-icon-soft"><x-icon name="video" class="cup-icon-md" /></div>
                <h3 class="card-title">1. CupTV Free Live Video</h3>
                <p class="card-desc">Free live video chat to meet verified members face-to-face across India and worldwide, with one-tap Next and instant report.</p>
                <a href="{{ route('live-video.index') }}" class="text-link mt-8">Open CupTV Live</a>
            </article>
            <article class="minimal-card">
                <div class="card-icon-soft"><x-icon name="heart" class="cup-icon-md" /></div>
                <h3 class="card-title">2. Shareable Coffee Moments</h3>
                <p class="card-desc">Women and members can share café stories &amp; photos safely to CupDate, WhatsApp, Instagram, X, and Facebook.</p>
                <a href="{{ route('moments.index') }}" class="text-link mt-8">Explore Moments →</a>
            </article>
            <article class="minimal-card">
                <div class="card-icon-soft"><x-icon name="shield" class="cup-icon-md" /></div>
                <h3 class="card-title">3. Clean, No-Nudity &amp; Anti-Spam</h3>
                <p class="card-desc">Automated text &amp; photo moderation blocks spam links and explicit content before it ever reaches your inbox.</p>
                <a href="{{ route('safety') }}" class="text-link mt-8">Safety Standards →</a>
            </article>
        </div>
    </div>
</section>

<section class="section-grey">
    <div class="container">
        <div class="discover-header-row">
            <div>
                <span class="eyebrow-text">REAL PROFILES · SEARCH &amp; EXPLORE</span>
                <h2 class="section-title">Search people, cities, games &amp; travel groups</h2>
            </div>
            <a href="{{ route('public.discover') }}" class="text-link">Browse all profiles →</a>
        </div>

        <form method="GET" action="{{ route('public.discover') }}" class="home-search-strip mt-12">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search people by name, city (Shimla, Delhi, Pune) or interest..." aria-label="Search people and cities">
            <button type="submit" class="btn btn-pink btn-sm">Search People</button>
            <a href="{{ route('games.truth-dare') }}" class="btn btn-coffee btn-sm">Play Truth &amp; Dare</a>
            <a href="{{ route('travel.groups') }}" class="btn btn-outline btn-sm">Travel Groups</a>
        </form>

        <div class="filter-bar" role="region" aria-label="Discover Filters">
            <a href="{{ route('home', ['filter' => 'nearby']) }}" class="filter-chip {{ $activeFilter === 'nearby' && !$activeCity ? 'is-active' : '' }}">Nearby</a>
            <a href="{{ route('home', ['filter' => 'new']) }}" class="filter-chip {{ $activeFilter === 'new' ? 'is-active' : '' }}">New Members</a>
            <a href="{{ route('home', ['filter' => 'popular']) }}" class="filter-chip {{ $activeFilter === 'popular' ? 'is-active' : '' }}">Popular</a>

            <form method="GET" action="{{ route('home') }}" class="filter-selects">
                <select name="interest" onchange="this.form.submit()" aria-label="Filter by Interest">
                    <option value="">All Interests</option>
                    @foreach ($interests as $interest)
                        @php $interestName = is_object($interest) ? $interest->name : (is_array($interest) ? ($interest['name'] ?? '') : (string) $interest); @endphp
                        <option value="{{ $interestName }}" @selected($activeInterest === $interestName)>{{ $interestName }}</option>
                    @endforeach
                </select>
                <select name="city" onchange="this.form.submit()" aria-label="Filter by City">
                    <option value="">All Cities</option>
                    @foreach ($cities as $city)
                        @php $cityName = is_object($city) ? $city->name : (is_array($city) ? ($city['name'] ?? '') : (string) $city); @endphp
                        <option value="{{ $cityName }}" @selected($activeCity === $cityName)>{{ $cityName }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="mobile-quick-cities" aria-label="Popular City Guides">
            @foreach (['new-delhi' => 'Delhi', 'mumbai' => 'Mumbai', 'bengaluru' => 'Bengaluru', 'shimla' => 'Shimla', 'dharamshala' => 'Dharamshala', 'chandigarh' => 'Chandigarh', 'pune' => 'Pune', 'jaipur' => 'Jaipur'] as $cSlug => $cLabel)
                <a href="{{ route('seo.city', $cSlug) }}" class="filter-chip">{{ $cLabel }}</a>
            @endforeach
            <a href="{{ route('cities') }}" class="filter-chip is-active">Explore Cities →</a>
        </div>

        <div class="skeleton-grid-wrap mt-20" data-skeleton-container>
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
                @forelse ($featuredUsers as $person)
                <article class="profile-card-public js-profile-preview"
                         data-name="{{ $person->firstName() }}@if($person->ageNumber()), {{ $person->ageNumber() }}@endif"
                         data-city="{{ $person->city }}, {{ $person->state }}"
                         data-coffee="{{ $person->coffee_preference ?? 'Cappuccino' }}"
                         data-bio="{{ $person->bio ?: 'Enjoying good coffee and genuine conversation.' }}"
                         data-avatar="{{ $person->avatarUrl() }}"
                         data-verified="{{ $person->is_verified ? 'Verified Member' : 'Member' }}">
                    <div class="profile-card-img-wrap">
                        <img src="{{ $person->avatarUrl() }}" alt="{{ $person->firstName() }} in {{ $person->city }}" loading="lazy" decoding="async">
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
                        <p class="profile-card-bio">{{ Str::limit($person->bio ?: 'Enjoying good coffee and genuine conversation.', 90) }}</p>
                        <div class="tag-list">
                            @foreach (array_slice($person->interestsList(), 0, 3) as $tag)
                                <span class="tag-soft">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <div class="profile-card-actions-row">
                            <button type="button" class="btn btn-outline btn-sm js-open-profile-modal">View Profile</button>
                            <a href="{{ auth()->check() ? route('app.discover', ['q' => $person->firstName()]) : route('register') }}" class="btn btn-pink btn-sm">
                                Join Free
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state-card">
                    <h3>No matching profiles for this filter yet</h3>
                    <p>Try another city or interest, or create your free CupDate profile today.</p>
                    <a href="{{ route('register') }}" class="btn btn-pink btn-sm">Create Free Account</a>
                </div>
            @endforelse
            </div>
        </div>
    </div>
</section>

<section class="section-cream">
    <div class="container coffee-feature-grid">
        <div>
            <span class="eyebrow-text">FREE COFFEE DATING COMMUNITY</span>
            <h2 class="section-title">{{ $coffeeHeading }}</h2>
            <p class="section-lead">Meet over real coffee, connect on free CupTV live video, or share your coffee moments with friends - always 100% free.</p>
            <div class="hero-actions mt-16">
                <a href="{{ auth()->check() ? route('app.coffee-dates') : route('register') }}" class="btn btn-coffee">
                    Plan Free Coffee Date
                </a>
                <a href="{{ route('cafes.index') }}" class="btn btn-pink">
                    Read &amp; Write Café Reviews
                </a>
                <a href="{{ route('live-video.index') }}" class="btn btn-outline">
                    Try CupTV Now
                </a>
            </div>
        </div>
        <div class="cafe-places-stack">
            @foreach ($datePlaces as $place)
                @php
                    $placeName = is_object($place) ? $place->name : (is_array($place) ? ($place['name'] ?? '') : (string) $place);
                    $placeAddress = is_object($place) ? $place->address : (is_array($place) ? ($place['address'] ?? '') : '');
                    $placeRating = is_object($place) ? $place->rating : (is_array($place) ? ($place['rating'] ?? 4.8) : 4.8);
                @endphp
                <div class="cafe-spot-card">
                    <div class="cafe-spot-icon"><x-icon name="coffee" class="cup-icon-sm" /></div>
                    <div>
                        <div class="cafe-spot-name">{{ $placeName }}</div>
                        <div class="cafe-spot-address">{{ $placeAddress }}</div>
                    </div>
                    <span class="cafe-rating">{{ number_format((float) $placeRating, 1) }} / 5</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container editorial-grid">
        <div class="editorial-media">
            <img src="{{ asset('images/cupdate-scene.svg') }}" alt="CupDate free coffee dates, CupTV live video, and verified cafe reviews" loading="lazy" decoding="async" class="scene-illustration">
        </div>
        <div class="editorial-copy">
            <span class="eyebrow-text">MOBILE-FIRST · CAFE REVIEWS · EXPLORE CITIES</span>
            <h2 class="section-title">Girls &amp; members share café reviews, moments, and free live video</h2>
            <p class="section-lead">Browse women-friendly café reviews before meeting, share your favorite coffee moments on social media, and explore dating guides across {{ $totalSeoCities }}+ Indian &amp; global cities.</p>
            <div class="explore-cities-banner">
                <div>
                    <strong>{{ $totalSeoCities }}+ Cities &amp; Countries Covered</strong>
                    <span>Clean, dedicated SEO guides without homepage clutter</span>
                </div>
                <a href="{{ route('cities') }}" class="btn btn-pink btn-sm">Explore Cities →</a>
            </div>
            <div class="hero-actions mt-16">
                <a href="{{ route('cafes.index') }}" class="btn btn-coffee btn-sm">Explore Café Reviews</a>
                <a href="{{ route('moments.index') }}" class="btn btn-outline btn-sm">Share Coffee Moments</a>
            </div>
        </div>
    </div>
</section>

<section class="section-blush">
    <div class="container">
        <div class="section-heading centered">
            <span class="eyebrow-text">SAFETY &amp; TRUST</span>
            <h2 class="section-title">No Nudity. No Spam. Always Safe.</h2>
        </div>

        <div class="safety-grid">
            <div class="safety-pill"><x-icon name="shield" class="cup-icon-sm" /> Anti-nudity image check</div>
            <div class="safety-pill"><x-icon name="lock" class="cup-icon-sm" /> Anti-spam message filter</div>
            <div class="safety-pill"><x-icon name="user" class="cup-icon-sm" /> Privacy settings</div>
            <div class="safety-pill"><x-icon name="heart" class="cup-icon-sm" /> Safe Moments sharing</div>
            <div class="safety-pill"><x-icon name="shield" class="cup-icon-sm" /> Instant report &amp; block</div>
            <div class="safety-pill"><x-icon name="sparkles" class="cup-icon-sm" /> Role-based human moderation</div>
        </div>
        <div class="centered mt-24">
            <a href="{{ route('safety') }}" class="btn btn-outline btn-sm">Read Full Safety Standards</a>
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container narrow">
        <div class="section-heading centered">
            <span class="eyebrow-text">FAQ</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
        </div>

        <div class="faq-list" data-faq-accordion>
            @foreach ($faqs as $idx => $faq)
                <div class="faq-item {{ $idx === 0 ? 'is-open' : '' }}">
                    <button type="button" class="faq-question" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
                        <span>{{ $faq['q'] }}</span>
                        <span class="faq-chevron" aria-hidden="true">+</span>
                    </button>
                    <div class="faq-answer"><p>{{ $faq['a'] }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div id="profilePreviewModal" class="cup-modal-backdrop" hidden>
    <div class="cup-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalProfileName">
        <button type="button" class="cup-modal-close" id="closeProfileModal" aria-label="Close">×</button>
        <img id="modalProfileImg" src="" alt="" class="cup-modal-avatar">
        <span class="free-badge-pill mt-12" id="modalProfileBadge">Verified Member</span>
        <h3 id="modalProfileName" class="mt-8"></h3>
        <p class="muted-small" id="modalProfileCity"></p>
        <p id="modalProfileBio" class="mt-12"></p>
        <p class="muted-small mt-8">Favorite Coffee: <strong id="modalProfileCoffee"></strong></p>
        <div class="hero-actions mt-16">
            <a href="{{ auth()->check() ? route('app.discover') : route('register') }}" class="btn btn-pink btn-full">
                Join Free to Chat &amp; Video Call
            </a>
        </div>
    </div>
</div>
@endsection
