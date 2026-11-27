@extends('layouts.app')

@section('title', \App\Models\SiteSetting::getValue('seo_default_title', 'CupDate | Free Coffee Dating and CupTV Live Video'))

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
<section class="hero-section">
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
            <div class="hero-couple-showcase">
                <div class="hero-couple-main">
                    <img src="{{ asset('images/hero-couple-walk.jpg') }}" alt="Smiling couple walking arm in arm past fresh outdoor flowers on a weekend date" class="hero-couple-img" fetchpriority="high" decoding="async">
                    <div class="hero-couple-caption">
                        <strong>Real Connections, Not Endless Swipes</strong>
                        <span>Free Coffee Dates · {{ $totalSeoCities }}+ Cities · Love &amp; Astrology</span>
                    </div>
                </div>
                <div class="hero-couple-pair">
                    <figure class="hero-couple-thumb">
                        <img src="{{ asset('images/real-couple-shimla.jpg') }}" alt="Couple smiling beside a sunlit Himalayan wooden cafe window over warm coffee" loading="eager" decoding="async">
                        <figcaption>Shimla &amp; Himachal Cafes</figcaption>
                    </figure>
                    <figure class="hero-couple-thumb">
                        <img src="{{ asset('images/real-couple-delhi.jpg') }}" alt="Indian couple laughing over afternoon coffee at an outdoor garden cafe in New Delhi" loading="eager" decoding="async">
                        <figcaption>Delhi, Mumbai &amp; Global</figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="cup-marquee-band" aria-label="CupDate Platform Highlights">
    <div class="cup-marquee-track">
        @for ($loopIdx = 0; $loopIdx < 2; $loopIdx++)
            <div class="cup-marquee-item"><span class="cup-marquee-serif">Zero Subscription Paywalls</span><span>/</span><span class="cup-marquee-mono">100% Free Forever</span></div>
            <div class="cup-marquee-item"><span class="cup-marquee-serif">Real Conversations Over Coffee</span><span>/</span><span class="cup-marquee-mono">{{ $totalSeoCities }}+ Cities Indexed</span></div>
            <div class="cup-marquee-item"><span class="cup-marquee-serif">Verified 18+ Community</span><span>/</span><span class="cup-marquee-mono">Anti-Nudity &amp; Anti-Spam</span></div>
            <div class="cup-marquee-item"><span class="cup-marquee-serif">Love, Astrology &amp; Travel Groups</span><span>/</span><span class="cup-marquee-mono">CupTV Live Video</span></div>
        @endfor
    </div>
</div>

<section class="section-white">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow-text">EDITORIAL PHOTOGRAPHY · GENUINE MEETUPS</span>
            <h2 class="section-title">Designed for Real-World Chemistry</h2>
        </div>
        <div class="cup-bento-grid">
            <article class="cup-bento-card col-7">
                <img src="{{ asset('images/hero-couple-walk.jpg') }}" alt="Couple enjoying an afternoon flower market walk together" loading="lazy" decoding="async">
                <div class="cup-bento-overlay">
                    <span class="cup-bento-tag">Public Daytime Dates</span>
                    <h3>Intentional Meetups Across India &amp; Worldwide</h3>
                    <p>Start with respectful conversation, move to a quick video hello, and meet comfortably in bright daytime venues.</p>
                </div>
            </article>
            <article class="cup-bento-card col-5">
                <img src="{{ asset('images/real-couple-shimla.jpg') }}" alt="Couple talking over hot cappuccinos at a sunlit cafe window" loading="lazy" decoding="async">
                <div class="cup-bento-overlay">
                    <span class="cup-bento-tag">Women-Friendly Cafes</span>
                    <h3>Curated Café Discovery</h3>
                    <p>Read honest community café notes before choosing your first coffee spot.</p>
                </div>
            </article>
            <article class="cup-bento-card col-6">
                <img src="{{ asset('images/real-couple-delhi.jpg') }}" alt="Couple sharing laughter over outdoor espresso and pastries" loading="lazy" decoding="async">
                <div class="cup-bento-overlay">
                    <span class="cup-bento-tag">Free Messaging &amp; Video</span>
                    <h3>Talk Face-to-Face Before You Meet</h3>
                    <p>Built-in encrypted 1-to-1 video calls and CupTV speed rooms at zero cost.</p>
                </div>
            </article>
            <article class="cup-bento-card col-6">
                <img src="{{ asset('images/couple-cafe-1.jpg') }}" alt="Young couple over coffee cups beside mountain trees" loading="lazy" decoding="async">
                <div class="cup-bento-overlay">
                    <span class="cup-bento-tag">Travel &amp; Truth or Dare</span>
                    <h3>Group Travel &amp; Icebreakers</h3>
                    <p>Explore Himachal, Spiti, Goa, or Jaipur travel groups and play clean Truth or Dare prompts.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section-cream">
    <div class="container">
        <div class="section-heading centered">
            <span class="eyebrow-text">HOW CUPDATE WORKS</span>
            <h2 class="section-title">Three Calm Steps to a Real Coffee Date</h2>
        </div>
        <div class="cup-steps-grid">
            <article class="cup-step-card is-maroon">
                <span class="cup-step-num">01 / PROFILE</span>
                <h3>Create Your 18+ Account Free</h3>
                <p>Protected with cryptographic password hashing, privacy controls, and city preferences. No subscriptions ever.</p>
            </article>
            <article class="cup-step-card is-grey">
                <span class="cup-step-num">02 / DISCOVERY</span>
                <h3>Chat, Check Compatibility &amp; Video</h3>
                <p>Send free messages, explore Love &amp; Zodiac guides, play Truth or Dare, or verify chemistry on CupTV live video.</p>
            </article>
            <article class="cup-step-card is-stone">
                <span class="cup-step-num">03 / MEETUP</span>
                <h3>Meet at Safe Daytime Cafés</h3>
                <p>Browse community-reviewed women-friendly coffee houses across {{ $totalSeoCities }}+ cities before scheduling a meetup.</p>
            </article>
        </div>

        <div class="cup-chemistry-box mt-24" data-chemistry-explorer>
            <div class="cup-chem-left">
                <span class="cup-chem-label">CUPDATE CHEMISTRY &amp; CONVERSATION DIAL</span>
                <h3 class="card-title">Preview Your Preferred Dating Pace</h3>
                <div>
                    <label for="chemPaceRange" class="cup-chem-label">Conversation Tempo: <strong id="chemPaceLabel">Thoughtful &amp; Warm</strong></label>
                    <input type="range" id="chemPaceRange" min="1" max="3" value="2" class="cup-chem-dial mt-8">
                </div>
                <div>
                    <label for="chemSettingRange" class="cup-chem-label">Ideal First Spot: <strong id="chemSettingLabel">Neighborhood Coffeehouse</strong></label>
                    <input type="range" id="chemSettingRange" min="1" max="3" value="2" class="cup-chem-dial mt-8">
                </div>
                <div class="hero-actions">
                    <a href="{{ route('astrology.index') }}" class="btn btn-outline btn-xs">Explore 156+ Zodiac Guides</a>
                    <a href="{{ route('games.truth-dare') }}" class="btn btn-blush btn-xs">Try Truth or Dare</a>
                </div>
            </div>
            <div class="cup-chem-right">
                <span class="cup-chem-label">SUGGESTED DATE FORMAT</span>
                <h3 class="cup-chem-result-title" id="chemResultTitle">Relaxed Afternoon Cappuccino &amp; Real Banter</h3>
                <p class="cup-chem-result-desc" id="chemResultDesc">Take a 5-minute CupTV video hello first, then meet during daylight at a comfortable neighborhood coffee shop for unhurried conversation.</p>
                <a href="{{ route('register') }}" class="btn btn-outline btn-sm" style="align-self:flex-start;">Start Free on CupDate</a>
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
            <a href="{{ route('home', ['filter' => 'nearby']) }}" rel="nofollow" class="filter-chip {{ $activeFilter === 'nearby' && !$activeCity ? 'is-active' : '' }}">Nearby</a>
            <a href="{{ route('home', ['filter' => 'new']) }}" rel="nofollow" class="filter-chip {{ $activeFilter === 'new' ? 'is-active' : '' }}">New Members</a>
            <a href="{{ route('home', ['filter' => 'popular']) }}" rel="nofollow" class="filter-chip {{ $activeFilter === 'popular' ? 'is-active' : '' }}">Popular</a>

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
                         data-bio="{{ $person->bio ?? '' }}"
                         data-avatar="{{ $person->avatarUrl() ?? '' }}"
                         data-verified="{{ $person->is_verified ? 'Verified Member' : 'Member' }}">
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
                            <p class="profile-card-bio">{{ Str::limit($person->bio, 90) }}</p>
                        @endif
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
    <div class="container">
        <div class="legal-trust-panel">
            <div>
                <span class="eyebrow-text">GENUINE LEGAL DATING PLATFORM · 18+ ONLY</span>
                <h2 class="section-title">Why CupDate Is Built as a Safe, Transparent Indian &amp; Global Dating Platform</h2>
                <p class="section-lead">CupDate (cupdate.in) connects consenting adults aged 18+ for honest friendship, coffee dates, and serious relationships with published policies, active moderation, and zero hidden charges.</p>
                <div class="legal-links-row">
                    <a href="{{ route('privacy') }}" class="btn btn-outline btn-xs">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="btn btn-outline btn-xs">Terms &amp; Conditions</a>
                    <a href="{{ route('guidelines') }}" class="btn btn-outline btn-xs">Community Guidelines</a>
                    <a href="{{ route('safety-center') }}" class="btn btn-outline btn-xs">Safety Center</a>
                    <a href="{{ route('contact') }}" class="btn btn-blush btn-xs">Contact Support</a>
                </div>
            </div>
            <div class="legal-facts-list">
                <div class="legal-fact-item">
                    <strong>Mandatory 18+ Age Check</strong>
                    <span>Only adults 18 years or older can register, message, or join video rooms.</span>
                </div>
                <div class="legal-fact-item">
                    <strong>Hashed Credentials &amp; Private Locations</strong>
                    <span>Passwords use cryptographic hashing and exact street coordinates are never exposed.</span>
                </div>
                <div class="legal-fact-item">
                    <strong>Zero Paywalls &amp; Honest Community</strong>
                    <span>Free messaging, free CupTV video, real café reviews, and instant user report/block controls.</span>
                </div>
            </div>
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
