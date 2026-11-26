@extends('layouts.app')

@section('title', 'Login to CupDate')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="auth-section section-cream">
    <div class="container auth-grid">
        <div>
            <span class="eyebrow-pill"><x-icon name="coffee" class="cup-icon-xs" /> Welcome back</span>
            <h1 class="hero-title">Continue your coffee conversations.</h1>
            <p class="hero-subtitle">Sign in securely to view matches, send messages, start WebRTC video calls, and plan coffee dates across Himachal Pradesh.</p>
        </div>

        <div class="auth-card">
            <h2 class="card-title">Sign in with Email</h2>

            @if (session('info'))
                <div class="alert-info">{{ session('info') }}</div>
            @endif
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="cupdate-form mt-16">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div class="auth-meta-row">
                    <label class="checkbox-inline"><input type="checkbox" name="remember"> Keep me signed in</label>
                    <a href="{{ route('password.request') }}" class="text-link">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-pink btn-full">Login to CupDate</button>
            </form>

            <div class="auth-divider"><span>or</span></div>

            <div class="auth-alt-actions">
                <form method="POST" action="{{ route('auth.guest') }}">
                    @csrf
                    <button type="submit" class="btn btn-blush btn-full">Continue as Guest</button>
                </form>
            </div>

            <p class="auth-switch">New to CupDate? <a href="{{ route('register') }}" class="text-link">Create Free Account</a></p>
        </div>
    </div>
</section>
@endsection
