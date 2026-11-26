<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'CupDate Admin Console')</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/cupdate-logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cupdate-body admin-body">
    <header class="admin-topbar">
        <div class="admin-topbar-inner">
            <div class="admin-brand-group">
                <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                    <img src="{{ asset('images/cupdate-logo.svg') }}" alt="CupDate" class="brand-logo-img" width="32" height="32">
                    <span class="brand-text">CupDate <span>Admin</span></span>
                </a>
                <span class="admin-role-pill">{{ strtoupper(str_replace('_', ' ', auth()->user()->role ?? 'super_admin')) }}</span>
            </div>

            <nav class="admin-header-nav" aria-label="Admin Navigation">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Overview</a>
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'is-active' : '' }}">Users</a>
                @if (auth()->user()->hasAdminRole('super_admin', 'moderator', 'support_admin'))
                    <a href="{{ route('admin.moderation') }}" class="{{ request()->routeIs('admin.moderation') ? 'is-active' : '' }}">Moderation</a>
                @endif
                @if (auth()->user()->hasAdminRole('super_admin', 'content_manager'))
                    <a href="{{ route('admin.content') }}" class="{{ request()->routeIs('admin.content') ? 'is-active' : '' }}">Content</a>
                    <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'is-active' : '' }}">SEO &amp; Settings</a>
                @endif
            </nav>

            <div class="admin-header-actions">
                <a href="{{ route('home') }}" class="btn btn-outline btn-xs">Public Site</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="btn btn-coffee btn-xs">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container admin-main">
        @if (session('success'))
            <div class="alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-error" role="alert">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
