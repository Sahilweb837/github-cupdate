<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $canonicalUrl = trim($__env->yieldContent('canonical')) ?: \App\Services\SeoService::canonicalForRequest(request());
        $robotsContent = trim($__env->yieldContent('robots')) ?: (request()->hasAny(['filter', 'sort', 'q', 'city', 'interest']) ? 'noindex, follow' : 'index, follow, max-image-preview:large');
    @endphp
    <title>@yield('title', \App\Models\SiteSetting::getValue('seo_default_title', 'CupDate — Meet Someone. Share a Coffee. Create a Connection.'))</title>
    <meta name="description" content="@yield('description', \App\Models\SiteSetting::getValue('seo_default_description', 'CupDate is a genuine, safe dating and social connection platform in Himachal Pradesh for friendship, chat, video calls, and coffee dates.'))">
    <meta name="robots" content="{{ $robotsContent }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="CupDate">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'CupDate — Meet Someone. Share a Coffee. Create a Connection.')">
    <meta property="og:description" content="@yield('description', 'Meet genuine people around you in Himachal Pradesh, chat safely, video call, and share coffee dates.')">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="@yield('og_image', asset('images/hero-coffee-couple.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'CupDate')">
    <meta name="twitter:description" content="@yield('description', 'Meet Someone. Share a Coffee. Create a Connection.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "CupDate",
        "url": "https://cupdate.in",
        "logo": "{{ asset('images/cafe-blue-bottle.jpg') }}",
        "slogan": "Meet Someone. Share a Coffee. Create a Connection."
    }
    </script>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cupdate-body">
    <div id="cupPageProgress" class="page-progress-bar" aria-hidden="true"></div>

    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand-logo" aria-label="CupDate Home">
                <span class="brand-mark">
                    <x-icon name="coffee" class="cup-icon-sm" />
                </span>
                <span class="brand-text">Cup<span>Date</span></span>
            </a>

            <nav class="site-nav" id="siteNav" aria-label="Primary Navigation">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                <a href="{{ route('public.discover') }}" class="nav-link {{ request()->routeIs('public.discover') ? 'is-active' : '' }}">Discover</a>
                <a href="{{ route('how-it-works') }}" class="nav-link {{ request()->routeIs('how-it-works') ? 'is-active' : '' }}">How It Works</a>
                <a href="{{ route('safety') }}" class="nav-link {{ request()->routeIs('safety') ? 'is-active' : '' }}">Safety</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About Us</a>
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
                    <a href="{{ route('register') }}" class="btn btn-pink btn-sm desktop-only-btn">Create Free Account</a>
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
                    <span class="brand-mark"><x-icon name="coffee" class="cup-icon-sm" /></span>
                    <span class="brand-text">CupDate</span>
                </a>
                <p class="footer-tagline">Meet Someone. Share a Coffee. Create a Connection.</p>
                <p class="footer-sub">Genuine dating, meaningful friendships, and coffee meetups across Himachal Pradesh.</p>
            </div>

            <div>
                <h3 class="footer-heading">Explore</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('blog') }}">Blog</a></li>
                    <li><a href="{{ route('how-it-works') }}">How It Works</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-heading">Himachal Guides</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('seo.himachal') }}">Dating in Himachal Pradesh</a></li>
                    <li><a href="{{ route('seo.dharamshala') }}">Meet Singles in Dharamshala</a></li>
                    <li><a href="{{ route('seo.kangra') }}">Meet Singles in Kangra</a></li>
                    <li><a href="{{ route('seo.mandi') }}">Meet Singles in Mandi</a></li>
                    <li><a href="{{ route('seo.coffee-ideas') }}">Coffee Date Ideas in HP</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-heading">Trust &amp; Legal</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('guidelines') }}">Community Guidelines</a></li>
                    <li><a href="{{ route('safety-center') }}">Safety Center</a></li>
                </ul>
            </div>
        </div>

        <div class="container footer-bottom">
            <p>&copy; 2026 CupDate. All Rights Reserved.</p>
            <div class="footer-socials" aria-label="Social Media">
                <a href="https://cupdate.in" rel="noopener">cupdate.in</a>
                <span>·</span>
                <a href="{{ route('contact') }}">Support</a>
            </div>
        </div>
    </footer>
</body>
</html>
