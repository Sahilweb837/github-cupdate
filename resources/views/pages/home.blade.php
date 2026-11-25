@extends('layouts.app')

@section('title', 'Cupdate — Dating that updates with you')

@section('content')
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow-pill">
                <span aria-hidden="true">☕</span> Coffee first, chemistry next
            </div>

            <h1 class="hero-title">
                Dating that<br>
                <em class="accent-italic">updates</em> with you.
            </h1>

            <p class="hero-subtitle">
                Cupdate matches you on who you are today, not who you were at signup. Real people, live compatibility, and dates that actually happen.
            </p>

            <div class="hero-actions">
                <a href="{{ route('join') }}" class="btn btn-coral">
                    Start matching <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('how-it-works') }}" class="btn btn-outline">
                    <span class="play-triangle" aria-hidden="true">▶</span> See how it works
                </a>
            </div>

            <div class="social-proof">
                <div class="avatar-stack" aria-hidden="true">
                    <img src="{{ asset('images/member-amara.jpg') }}" alt="">
                    <img src="{{ asset('images/member-ben.jpg') }}" alt="">
                    <img src="{{ asset('images/member-yuki.jpg') }}" alt="">
                    <img src="{{ asset('images/member-diego.jpg') }}" alt="">
                </div>
                <p class="social-proof-text">
                    <strong>2.4M members</strong> · 18k dates booked this week
                </p>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-card-backdrop" aria-hidden="true"></div>
            <div class="hero-photo-frame">
                <img src="{{ asset('images/hero-coffee-couple.jpg') }}" alt="Couple smiling over coffee at a sunlit café" class="hero-photo">

                <div class="floating-badge badge-top-right">
                    <span class="badge-icon-soft" aria-hidden="true">📅</span>
                    <span>Date: Sat · 4pm</span>
                </div>

                <div class="floating-badge badge-match">
                    <span class="badge-heart" aria-hidden="true">❤</span>
                    <div>
                        <div class="badge-match-title">It’s a match!</div>
                        <div class="badge-match-sub">94% compatible</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-strip" aria-label="Cupdate metrics">
    <div class="container stats-grid">
        @foreach ($stats as $stat)
            <div class="stat-item">
                <div class="stat-value">{{ $stat['value'] }}</div>
                <div class="stat-label">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow-text">WHY CUPDATE</span>
            <h2 class="section-title">Less swiping. More meeting.</h2>
        </div>

        <div class="cards-grid-3">
            @foreach ($features as $feature)
                <article class="feature-card">
                    <div class="feature-icon" aria-hidden="true">
                        @if ($feature['icon'] === 'radar')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                            </svg>
                        @elseif ($feature['icon'] === 'spark')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 3v3m0 12v3M3 12h3m12 0h3M6.3 6.3l2.1 2.1m7.2 7.2 2.1 2.1m0-11.4-2.1 2.1M8.4 15.6l-2.1 2.1"/>
                            </svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 3 5 6v6c0 4.5 3 8.2 7 9.5 4-1.3 7-5 7-9.5V6l-7-3Z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        @endif
                    </div>
                    <h3 class="feature-title">{{ $feature['title'] }}</h3>
                    <p class="feature-desc">{{ $feature['description'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="blush-steps-band">
    <div class="container steps-grid-3">
        @foreach ($steps as $step)
            <div class="blush-step">
                <div class="blush-step-num">{{ $step['number'] }}</div>
                <h3 class="blush-step-title">{{ $step['title'] }}</h3>
                <p class="blush-step-desc">{{ $step['description'] }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading centered">
            <h2 class="section-title">Love stories, updated</h2>
        </div>

        <div class="stories-grid">
            @foreach ($stories as $story)
                <article class="story-card">
                    <p class="story-quote">{{ $story['quote'] }}</p>
                    <div class="story-author">
                        <img src="{{ asset($story['avatar']) }}" alt="{{ $story['names'] }}" class="story-avatar">
                        <div>
                            <div class="story-names">{{ $story['names'] }}</div>
                            <div class="story-location">{{ $story['location'] }}</div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-block cta-section-wrap">
    <div class="container">
        <div class="plum-cta-banner">
            <h2 class="cta-banner-title">
                Ready for your next <em class="accent-italic">update</em>?
            </h2>
            <p class="cta-banner-sub">Join free in under two minutes.</p>
            <a href="{{ route('join') }}" class="btn btn-coral">Create my profile</a>
        </div>
    </div>
</section>
@endsection
