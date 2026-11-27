<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#D96B89">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'CupDate App')</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/cupdate-logo.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/cupdate-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cupdate-body app-shell-body">
    <div id="cupPageProgress" class="page-progress-bar" aria-hidden="true"></div>

    <div class="mobile-app-topbar">
        <a href="{{ route('app.discover') }}" class="brand-logo">
            <img src="{{ asset('images/cupdate-logo.svg') }}" alt="CupDate.in" class="brand-logo-img" width="36" height="33">
            <span class="brand-text">CupDate<span>.in</span></span>
        </a>
        <div class="mobile-topbar-right">
            <a href="{{ route('app.notifications') }}" class="icon-circle-btn" aria-label="Notifications">
                <x-icon name="bell" class="cup-icon-sm" />
            </a>
            <a href="{{ route('app.profile') }}" class="mobile-avatar-link">
                <x-avatar :user="auth()->user()" :alt="auth()->user()->firstName()" />
            </a>
        </div>
    </div>

    <div class="app-shell">
        <aside class="app-sidebar">
            <div>
                <a href="{{ route('home') }}" class="brand-logo app-brand">
                    <img src="{{ asset('images/cupdate-logo.svg') }}" alt="CupDate.in" class="brand-logo-img" width="38" height="34">
                    <span class="brand-text">CupDate<span>.in</span></span>
                </a>

                <nav class="sidebar-nav" aria-label="App Navigation">
                    <a href="{{ route('app.discover') }}" class="sidebar-link {{ request()->routeIs('app.discover') ? 'is-active' : '' }}">
                        <x-icon name="compass" class="cup-icon-sm" /> Discover
                    </a>
                    <a href="{{ route('app.messages') }}" class="sidebar-link {{ request()->routeIs('app.messages') ? 'is-active' : '' }}">
                        <x-icon name="message" class="cup-icon-sm" /> Messages
                    </a>
                    <a href="{{ route('live-video.index') }}" class="sidebar-link {{ request()->routeIs('live-video.*') ? 'is-active' : '' }}">
                        <x-icon name="video" class="cup-icon-sm" /> CupTV Live Video
                    </a>
                    <a href="{{ route('app.video.index') }}" class="sidebar-link {{ request()->routeIs('app.video.*') ? 'is-active' : '' }}">
                        <x-icon name="video" class="cup-icon-sm" /> 1-to-1 Video Call
                    </a>
                    <a href="{{ route('moments.index') }}" class="sidebar-link {{ request()->routeIs('moments.*') ? 'is-active' : '' }}">
                        <x-icon name="heart" class="cup-icon-sm" /> Coffee Moments
                    </a>
                    <a href="{{ route('app.coffee-dates') }}" class="sidebar-link {{ request()->routeIs('app.coffee-dates') ? 'is-active' : '' }}">
                        <x-icon name="coffee" class="cup-icon-sm" /> Coffee Dates
                    </a>
                    <a href="{{ route('app.notifications') }}" class="sidebar-link {{ request()->routeIs('app.notifications') ? 'is-active' : '' }}">
                        <x-icon name="bell" class="cup-icon-sm" /> Notifications
                    </a>
                    <a href="{{ route('app.profile') }}" class="sidebar-link {{ request()->routeIs('app.profile') ? 'is-active' : '' }}">
                        <x-icon name="user" class="cup-icon-sm" /> Profile
                    </a>
                </nav>
            </div>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <x-avatar :user="auth()->user()" :alt="auth()->user()->full_name" class="sidebar-avatar" />
                    <div class="min-w-0">
                        <div class="sidebar-user-name">{{ auth()->user()->full_name }}</div>
                        <div class="sidebar-user-city">{{ auth()->user()->city }}</div>
                    </div>
                </div>
                <div class="sidebar-user-actions">
                    <a href="{{ route('home') }}" class="text-link">Public site</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="text-link btn-reset">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="app-main">
            @if (session('success'))
                <div class="alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <div id="incomingCallToast" class="incoming-call-banner" hidden>
                <div class="incoming-call-info">
                    <span class="pulse-dot"></span>
                    <div>
                        <strong id="incomingCallerName">Incoming video call</strong>
                        <p id="incomingCallerSub">Tap to join on CupDate Video</p>
                    </div>
                </div>
                <div class="incoming-call-actions">
                    <a id="incomingJoinLink" href="{{ route('app.video.index') }}" class="btn btn-pink btn-xs">Answer</a>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <nav class="mobile-bottom-nav" aria-label="Mobile App Navigation">
        <a href="{{ route('app.discover') }}" class="bottom-nav-item {{ request()->routeIs('app.discover') ? 'is-active' : '' }}">
            <x-icon name="compass" class="cup-icon-sm" />
            <span>Discover</span>
        </a>
        <a href="{{ route('app.messages') }}" class="bottom-nav-item {{ request()->routeIs('app.messages') ? 'is-active' : '' }}">
            <x-icon name="message" class="cup-icon-sm" />
            <span>Chat</span>
        </a>
        <a href="{{ route('live-video.index') }}" class="bottom-nav-item {{ request()->routeIs('live-video.*') ? 'is-active' : '' }}">
            <x-icon name="video" class="cup-icon-sm" />
            <span>CupTV</span>
        </a>
        <a href="{{ route('moments.index') }}" class="bottom-nav-item {{ request()->routeIs('moments.*') ? 'is-active' : '' }}">
            <x-icon name="heart" class="cup-icon-sm" />
            <span>Moments</span>
        </a>
        <a href="{{ route('app.profile') }}" class="bottom-nav-item {{ request()->routeIs('app.profile') ? 'is-active' : '' }}">
            <x-icon name="user" class="cup-icon-sm" />
            <span>Profile</span>
        </a>
    </nav>
</body>
</html>
