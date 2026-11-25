<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cupdate — Coffee first, chemistry next')</title>
    <meta name="description" content="@yield('description', 'Cupdate matches you on who you are today, not who you were at signup. Real people, live compatibility, and dates that actually happen.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cupdate-body">
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand-logo" aria-label="Cupdate Home">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1">
                        <path d="M12 20.5s-6.5-4.35-8.5-8.2C2 9.4 3.7 6 7 6c1.9 0 3.4 1.1 5 2.7C13.6 7.1 15.1 6 17 6c3.3 0 5 3.4 3.5 6.3-2 3.85-8.5 8.2-8.5 8.2Z" fill="currentColor" stroke="none"/>
                    </svg>
                </span>
                <span class="brand-text">Cupdate</span>
            </a>

            <nav class="site-nav" id="siteNav" aria-label="Primary">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                <a href="{{ route('how-it-works') }}" class="nav-link {{ request()->routeIs('how-it-works') ? 'is-active' : '' }}">How it works</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact</a>
            </nav>

            <div class="header-actions">
                @if (session()->has('cupdate_user'))
                    <a href="{{ route('app.discover') }}" class="nav-link">Open app</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-plum btn-sm">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                    <a href="{{ route('join') }}" class="btn btn-plum btn-sm">Join free</a>
                @endif
                <button type="button" class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <a href="{{ route('home') }}" class="footer-brand">Cupdate</a>
            <nav class="footer-links" aria-label="Footer">
                <a href="{{ route('privacy') }}">Privacy</a>
                <a href="{{ route('terms') }}">Terms</a>
                <a href="{{ route('safety') }}">Safety</a>
                <a href="{{ route('contact') }}">Contact</a>
            </nav>
            <p class="footer-copy">&copy; {{ date('Y') }} Cupdate</p>
        </div>
    </footer>
</body>
</html>
