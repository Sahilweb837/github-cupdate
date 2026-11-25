@extends('layouts.app')

@section('title', \App\Models\SiteSetting::getValue('seo_default_title', 'CupDate — Meet Someone. Share a Coffee. Create a Connection.'))

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
                <span>Meet Someone. Share a Coffee. Create a Connection.</span>
            </div>

            <h1 class="hero-title">{{ $headline }}</h1>
            <p class="hero-subtitle">{{ $subheadline }}</p>

            <div class="hero-actions">
                <a href="{{ auth()->check() ? route('app.discover') : route('register') }}" class="btn btn-pink">
                    Find Your Connection
                </a>
                <a href="{{ route('how-it-works') }}" class="btn btn-outline">
                    Explore How It Works
                </a>
            </div>

            <div class="trust-pills" aria-label="Platform Trust Highlights">
                <span><x-icon name="shield" class="cup-icon-xs" /> Genuine Profiles</span>
                <span><x-icon name="message" class="cup-icon-xs" /> Free Chat</span>
                <span><x-icon name="video" class="cup-icon-xs" /> Free Video Calling</span>
                <span><x-icon name="heart" class="cup-icon-xs" /> Safe Community</span>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-shape-blush" aria-hidden="true"></div>
            <div class="hero-photo-frame">
                <img src="{{ asset('images/hero-coffee-couple.jpg') }}" alt="Two people enjoying coffee together in a warm modern cafe" class="hero-photo">
                <div class="floating-badge badge-bottom-left">
                    <span class="badge-coffee-dot"><x-icon name="coffee" class="cup-icon-xs" /></span>
                    <div>
                        <div class="badge-title">Coffee First, Always</div>
                        <div class="badge-sub">Himachal Pradesh &amp; Beyond</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container">
        <div class="section-heading centered">
            <span class="eyebrow-text">HOW CUPDATE WORKS</span>
            <h2 class="section-title">Three simple steps to real connection</h2>
        </div>

        <div class="cards-grid-3">
            <article class="minimal-card">
                <div class="card-icon-soft"><x-icon name="compass" class="cup-icon-md" /></div>
                <h3 class="card-title">1. Discover People</h3>
                <p class="card-desc">Find interesting people nearby across Himachal Pradesh and explore genuine profiles.</p>
            </article>
            <article class="minimal-card">
                <div class="card-icon-soft"><x-icon name="message" class="cup-icon-md" /></div>
                <h3 class="card-title">2. Start Connecting</h3>
                <p class="card-desc">Send messages, make friends, start respectful conversations, or hop on a free video call.</p>
            </article>
            <article class="minimal-card">
                <div class="card-icon-soft"><x-icon name="coffee" class="cup-icon-md" /></div>
                <h3 class="card-title">3. Meet Over Coffee</h3>
                <p class="card-desc">Turn meaningful online connections into comfortable, real-life daytime coffee dates.</p>
            </article>
        </div>
    </div>
</section>

<section class="section-grey">
    <div class="container">
        <div class="discover-header-row">
            <div>
                <span class="eyebrow-text">FEATURED PEOPLE</span>
                <h2 class="section-title">Genuine members on CupDate</h2>
            </div>
            <a href="{{ route('public.discover') }}" class="text-link">Browse all profiles →</a>
        </div>

        <div class="filter-bar" role="region" aria-label="Discover Filters">
            <a href="{{ route('home', ['filter' => 'nearby']) }}" class="filter-chip {{ $activeFilter === 'nearby' && !$activeCity ? 'is-active' : '' }}">Nearby</a>
            <a href="{{ route('home', ['filter' => 'new']) }}" class="filter-chip {{ $activeFilter === 'new' ? 'is-active' : '' }}">New Members</a>
            <a href="{{ route('home', ['filter' => 'popular']) }}" class="filter-chip {{ $activeFilter === 'popular' ? 'is-active' : '' }}">Popular</a>

            <form method="GET" action="{{ route('home') }}" class="filter-selects">
                <select name="interest" onchange="this.form.submit()" aria-label="Filter by Interest">
                    <option value="">All Interests</option>
                    @foreach ($interests as $interest)
                        <option value="{{ $interest->name }}" @selected($activeInterest === $interest->name)>{{ $interest->name }}</option>
                    @endforeach
                </select>
                <select name="city" onchange="this.form.submit()" aria-label="Filter by City">
                    <option value="">All Cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->name }}" @selected($activeCity === $city->name)>{{ $city->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

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
                @forelse ($featuredUsers as $person)
                    <article class="profile-card-public">
                        <div class="profile-card-img-wrap">
                            <img src="{{ $person->avatarUrl() }}" alt="{{ $person->firstName() }} in {{ $person->city }}" loading="lazy">
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
                            <a href="{{ auth()->check() ? route('app.discover', ['q' => $person->firstName()]) : route('register') }}" class="btn btn-pink btn-full btn-sm">
                                Send Request
                            </a>
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

<section class="section-white">
    <div class="container editorial-grid">
        <div class="editorial-media">
            <img src="{{ asset('images/cafe-blue-bottle.jpg') }}" alt="Sunlit coffee shop table with two ceramic coffee cups" loading="lazy" class="editorial-photo">
        </div>
        <div class="editorial-copy">
            <span class="eyebrow-text">WHY CUPDATE</span>
            <h2 class="section-title">Designed for trust, comfort, and real-world chemistry</h2>
            <div class="feature-check-grid">
                <div><x-icon name="check" class="cup-icon-sm" /> Genuine profile verification</div>
                <div><x-icon name="check" class="cup-icon-sm" /> Free 1-to-1 messaging</div>
                <div><x-icon name="check" class="cup-icon-sm" /> Free WebRTC video calls</div>
                <div><x-icon name="check" class="cup-icon-sm" /> Nearby Himachal connections</div>
                <div><x-icon name="check" class="cup-icon-sm" /> Interest-based discovery</div>
                <div><x-icon name="check" class="cup-icon-sm" /> Granular privacy controls</div>
                <div><x-icon name="check" class="cup-icon-sm" /> Instant report &amp; block</div>
                <div><x-icon name="check" class="cup-icon-sm" /> Respectful, adult-free space</div>
            </div>
        </div>
    </div>
</section>

<section class="section-cream">
    <div class="container coffee-feature-grid">
        <div>
            <span class="eyebrow-text">COFFEE DATE CULTURE</span>
            <h2 class="section-title">{{ $coffeeHeading }}</h2>
            <p class="section-lead">Sometimes the best connections begin with a simple hello and a cup of coffee.</p>
            <a href="{{ auth()->check() ? route('app.coffee-dates') : route('register') }}" class="btn btn-coffee">
                Plan Your Coffee Date
            </a>
        </div>
        <div class="cafe-places-stack">
            @foreach ($datePlaces as $place)
                <div class="cafe-spot-card">
                    <div class="cafe-spot-icon"><x-icon name="coffee" class="cup-icon-sm" /></div>
                    <div>
                        <div class="cafe-spot-name">{{ $place->name }}</div>
                        <div class="cafe-spot-address">{{ $place->address }}</div>
                    </div>
                    <span class="cafe-rating">★ {{ number_format((float) $place->rating, 1) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container narrow centered">
        <span class="eyebrow-text">REAL STORIES ONLY</span>
        <h2 class="section-title">Authentic community experiences</h2>
        <div class="honest-story-card">
            <p>CupDate never publishes fabricated reviews or synthetic testimonials. Only verified members who have genuinely connected and met in person can submit a story for community review.</p>
            <a href="{{ auth()->check() ? route('app.coffee-dates') : route('register') }}" class="btn btn-outline btn-sm">Start Your Real Connection</a>
        </div>
    </div>
</section>

<section class="section-blush">
    <div class="container">
        <div class="section-heading centered">
            <span class="eyebrow-text">SAFETY &amp; TRUST</span>
            <h2 class="section-title">Your Safety Comes First.</h2>
        </div>

        <div class="safety-grid">
            <div class="safety-pill"><x-icon name="shield" class="cup-icon-sm" /> Profile verification</div>
            <div class="safety-pill"><x-icon name="lock" class="cup-icon-sm" /> Secure authentication</div>
            <div class="safety-pill"><x-icon name="user" class="cup-icon-sm" /> Privacy settings</div>
            <div class="safety-pill"><x-icon name="heart" class="cup-icon-sm" /> Community guidelines</div>
            <div class="safety-pill"><x-icon name="shield" class="cup-icon-sm" /> Report &amp; block user</div>
            <div class="safety-pill"><x-icon name="sparkles" class="cup-icon-sm" /> Human moderation</div>
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
@endsection
