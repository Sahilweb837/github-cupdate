<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cupdate App')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cupdate-body app-shell-body">
    <div class="app-shell">
        <aside class="app-sidebar">
            <a href="{{ route('home') }}" class="brand-logo app-brand">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 20.5s-6.5-4.35-8.5-8.2C2 9.4 3.7 6 7 6c1.9 0 3.4 1.1 5 2.7C13.6 7.1 15.1 6 17 6c3.3 0 5 3.4 3.5 6.3-2 3.85-8.5 8.2-8.5 8.2Z"/>
                    </svg>
                </span>
                <span class="brand-text">Cupdate</span>
            </a>

            <nav class="sidebar-nav" aria-label="App Navigation">
                <a href="{{ route('app.discover') }}" class="sidebar-link {{ request()->routeIs('app.discover') ? 'is-active' : '' }}">
                    <span class="sidebar-dot">✦</span> Discover
                </a>
                <a href="{{ route('app.radar') }}" class="sidebar-link {{ request()->routeIs('app.radar') ? 'is-active' : '' }}">
                    <span class="sidebar-dot">◎</span> Live Radar
                </a>
                <a href="{{ route('app.messages') }}" class="sidebar-link {{ request()->routeIs('app.messages') ? 'is-active' : '' }}">
                    <span class="sidebar-dot">✉</span> Messages
                </a>
                <a href="{{ route('app.dates') }}" class="sidebar-link {{ request()->routeIs('app.dates') ? 'is-active' : '' }}">
                    <span class="sidebar-dot">☕</span> Dates
                </a>
                <a href="{{ route('app.profile') }}" class="sidebar-link {{ request()->routeIs('app.profile') ? 'is-active' : '' }}">
                    <span class="sidebar-dot">☺</span> Profile
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <img src="{{ asset($user['avatar']) }}" alt="{{ $user['name'] }}" class="sidebar-avatar">
                    <div>
                        <div class="sidebar-user-name">{{ $user['name'] }}</div>
                        <div class="sidebar-user-city">{{ $user['city'] }}</div>
                    </div>
                </div>
                <div class="sidebar-user-actions">
                    <a href="{{ route('home') }}" class="text-link">Public site</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="text-link btn-reset">Sign out</button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="app-main">
            @yield('content')
        </div>
    </div>
</body>
</html>
