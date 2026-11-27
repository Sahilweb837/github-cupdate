<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#D96B89">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $canonicalUrl = trim($__env->yieldContent('canonical')) ?: \App\Services\SeoService::canonicalForRequest(request());
        $robotsContent = trim($__env->yieldContent('robots')) ?: (request()->hasAny(['filter', 'sort', 'q', 'city', 'interest']) ? 'noindex, follow' : 'index, follow, max-image-preview:large');
        $liveSnapshot = app(\App\Services\LiveStatsService::class)->snapshot();
    @endphp
    <title>@yield('title', \App\Models\SiteSetting::getValue('seo_default_title', 'CupDate | Free Coffee Dating and CupTV Live Video'))</title>
    <meta name="description" content="@yield('description', \App\Models\SiteSetting::getValue('seo_default_description', 'CupDate (cupdate.in) is a 100% free coffee dating, CupTV live video chat, and safe social connection website across 200+ Indian cities and worldwide.'))">
    <meta name="robots" content="{{ $robotsContent }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/cupdate-logo.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/cupdate-logo.jpg') }}">
    <meta property="og:site_name" content="CupDate">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'CupDate | Free CupTV Video and Coffee Dating')">
    <meta property="og:description" content="@yield('description', 'Meet verified people across India and global cities, chat free, use CupTV live video, and read cafe reviews.')">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="@yield('og_image', asset('images/cupdate-scene.svg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'CupDate')">
    <meta name="twitter:description" content="@yield('description', '100% Free Coffee & Live Video Dating Across India & Worldwide.')">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('preload')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "CupDate",
        "url": "https://cupdate.in",
        "logo": "{{ asset('images/cupdate-logo.svg') }}",
        "slogan": "Meet Someone. Share a Coffee. Create a Connection."
    }
    </script>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cupdate-body" data-live-stats-url="{{ route('live-stats') }}">
    <div id="cupPageProgress" class="page-progress-bar" aria-hidden="true"></div>

    <div class="top-promo-bar">
        <div class="container top-promo-inner">
            <div class="top-promo-left">
                <span class="free-badge-pill">100% FREE FOREVER · NO PAID SUBSCRIPTION</span>
                <span class="top-promo-text">Free Chat, CupTV Live Video &amp; Coffee Dates across 100+ Indian Cities &amp; Global</span>
            </div>
            <div class="top-promo-right">
                <span class="live-visitor-pill" title="Real-time active visitors right now">
                    <span class="online-dot"></span>
                    <strong data-live-visitors>{{ $liveSnapshot['active_visitors'] }}</strong> live ·
                    <strong data-live-members>{{ $liveSnapshot['online_members'] }}</strong> online
                </span>
                @guest
                    <a href="{{ route('register') }}" class="top-join-btn">Join Free →</a>
                @endguest
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand-logo" aria-label="CupDate.in Home">
                <img src="{{ asset('images/cupdate-logo.svg') }}" alt="CupDate.in Logo" class="brand-logo-img" width="40" height="36">
                <span class="brand-text">CupDate<span>.in</span></span>
            </a>

            <nav class="site-nav" id="siteNav" aria-label="Primary Navigation">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                <a href="{{ route('public.discover') }}" class="nav-link {{ request()->routeIs('public.discover') ? 'is-active' : '' }}">Discover</a>
                <a href="{{ route('live-video.index') }}" class="nav-link {{ request()->routeIs('live-video.*') ? 'is-active' : '' }}">CupTV Video</a>
                <a href="{{ route('games.truth-dare') }}" class="nav-link {{ request()->routeIs('games.*') ? 'is-active' : '' }}">Truth &amp; Dare</a>
                <a href="{{ route('travel.groups') }}" class="nav-link {{ request()->routeIs('travel.*') ? 'is-active' : '' }}">Travel</a>
                <a href="{{ route('astrology.index') }}" class="nav-link {{ request()->routeIs('astrology.*') ? 'is-active' : '' }}">Astrology</a>
                <a href="{{ route('cities') }}" class="nav-link {{ request()->routeIs('cities', 'seo.city') ? 'is-active' : '' }}">Cities</a>
            </nav>

            <div class="header-actions">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('app.discover') }}" class="btn btn-blush btn-sm">
                        {{ auth()->user()->isAdmin() ? 'Admin Panel' : 'Open App' }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="nav-link btn-reset">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link mobile-login-btn">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-pink btn-sm desktop-only-btn">Join Free Forever</a>
                @endauth
                <button type="button" class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    @if (\App\Models\SiteSetting::getValue('ads_enabled') === '1' && ($adHtml = \App\Models\SiteSetting::getValue('ads_slot_html')))
        <div class="container ad-slot-wrap">{!! $adHtml !!}</div>
    @endif

    <main id="mainContent">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand-col">
                <a href="{{ route('home') }}" class="brand-logo footer-logo">
                    <img src="{{ asset('images/cupdate-logo.svg') }}" alt="CupDate.in Logo" class="brand-logo-img" width="38" height="34">
                    <span class="brand-text">CupDate.in</span>
                </a>
                <p class="footer-tagline">Free Coffee Dating and CupTV Live Video Across 200+ Cities.</p>
                <p class="footer-sub">100% free messaging, CupTV live video chat, and community cafe reviews - zero paid paywalls, strict no-nudity &amp; anti-spam moderation.</p>
            </div>

            <div>
                <h3 class="footer-heading">Free Features</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('live-video.index') }}">CupTV Free Live Video</a></li>
                    <li><a href="{{ route('moments.index') }}">Coffee Moments &amp; Social Share</a></li>
                    <li><a href="{{ route('cafes.index') }}">Women-Friendly Café Reviews</a></li>
                    <li><a href="{{ route('public.discover') }}">Discover Free Profiles</a></li>
                    <li><a href="{{ route('cities') }}">Explore 200+ Indian &amp; World Cities</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-heading">Games, Love &amp; Travel</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('games.truth-dare') }}">Truth or Dare Online</a></li>
                    <li><a href="{{ route('travel.groups') }}">Travel Groups &amp; Road Trips</a></li>
                    <li><a href="{{ route('astrology.index') }}">Love &amp; Zodiac Compatibility</a></li>
                    <li><a href="{{ route('seo.speed-dating') }}">Free Video Speed Dating</a></li>
                    <li><a href="{{ route('seo.icebreakers') }}">Coffee Icebreaker Questions</a></li>
                    <li><a href="{{ route('seo.meetups') }}">Weekend Coffee Meetups</a></li>
                    <li><a href="{{ route('seo.couple-activities') }}">Daytime Couple Activities</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-heading">Top City Guides</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('seo.city', 'india') }}">Free Dating in India</a></li>
                    <li><a href="{{ route('seo.himachal') }}">Dating in Himachal Pradesh</a></li>
                    <li><a href="{{ route('seo.city', 'new-delhi') }}">Dating in New Delhi</a></li>
                    <li><a href="{{ route('seo.city', 'mumbai') }}">Dating in Mumbai</a></li>
                    <li><a href="{{ route('seo.city', 'bengaluru') }}">Dating in Bengaluru</a></li>
                    <li><a href="{{ route('seo.college') }}">College &amp; Campus Meetups</a></li>
                    <li><a href="{{ route('seo.solo-travel') }}">Solo Travel Companions</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-heading">Trust &amp; Legal</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact &amp; Support</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('safety-center') }}">Safety Center</a></li>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="footer-giant-brand" aria-hidden="true">CupDate</div>
        </div>

        <div class="container footer-bottom">
            <p>&copy; 2026 CupDate (cupdate.in). 100% Free Forever | 18+ Verified, Safe &amp; Moderated.</p>
            <div class="footer-socials" aria-label="Footer Quick Links">
                <a href="{{ route('astrology.index') }}">Astrology</a>
                <span>·</span>
                <a href="{{ route('cities') }}">1000+ City &amp; Love Guides</a>
                <span>·</span>
                <a href="{{ route('contact') }}">Support</a>
            </div>
        </div>
    </footer>

    <nav class="mobile-bottom-nav public-mobile-nav" aria-label="Mobile Quick Navigation">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'is-active' : '' }}">
            <x-icon name="coffee" class="cup-icon-sm" />
            <span>Home</span>
        </a>
        <a href="{{ route('public.discover') }}" class="bottom-nav-item {{ request()->routeIs('public.discover') ? 'is-active' : '' }}">
            <x-icon name="compass" class="cup-icon-sm" />
            <span>Discover</span>
        </a>
        <a href="{{ route('live-video.index') }}" class="bottom-nav-item {{ request()->routeIs('live-video.*') ? 'is-active' : '' }}">
            <x-icon name="video" class="cup-icon-sm" />
            <span>CupTV</span>
        </a>
        <a href="{{ route('moments.index') }}" class="bottom-nav-item {{ request()->routeIs('moments.*') ? 'is-active' : '' }}">
            <x-icon name="heart" class="cup-icon-sm" />
            <span>Moments</span>
        </a>
        <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('app.discover')) : route('register') }}" class="bottom-nav-item {{ request()->routeIs('register', 'login', 'app.*') ? 'is-active' : '' }}">
            <x-icon name="user" class="cup-icon-sm" />
            <span>{{ auth()->check() ? 'App' : 'Join Free' }}</span>
        </a>
    </nav>
</body>
</html>
