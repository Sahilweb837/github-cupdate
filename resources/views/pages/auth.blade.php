@extends('layouts.app')

@section('title', ($mode === 'join' ? 'Join free' : 'Log in') . ' - Cupdate')

@section('content')
<section class="auth-section">
    <div class="container auth-grid">
        <div class="auth-copy">
            <span class="eyebrow-pill">Coffee first, chemistry next</span>
            <h1 class="hero-title">
                {{ $mode === 'join' ? 'Create your' : 'Welcome back to' }}<br>
                <em class="accent-italic">Cupdate</em> {{ $mode === 'join' ? 'profile.' : 'today.' }}
            </h1>
            <p class="hero-subtitle">
                {{ $mode === 'join'
                    ? 'Join 2.4M verified members meeting for real coffee dates instead of endless swiping.'
                    : 'Check your daily curated picks, Live Radar nearby spots, and upcoming coffee dates.' }}
            </p>
        </div>

        <div class="auth-card">
            <div class="auth-tabs">
                <a href="{{ route('login') }}" class="auth-tab {{ $mode === 'login' ? 'is-active' : '' }}">Log in</a>
                <a href="{{ route('join') }}" class="auth-tab {{ $mode === 'join' ? 'is-active' : '' }}">Join free</a>
            </div>

            <form method="POST" action="{{ route('auth.submit') }}" class="cupdate-form">
                @csrf
                <input type="hidden" name="mode" value="{{ $mode }}">

                @if ($mode === 'join')
                    <div class="form-group">
                        <label for="name">First name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', 'Sofia') }}" required>
                        @error('name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input id="city" name="city" type="text" value="{{ old('city', 'Portland, OR') }}">
                    </div>
                @endif

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', 'sofia@cupdate.app') }}" required>
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" value="cupdate123" required>
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-coral btn-full">
                    {{ $mode === 'join' ? 'Create my profile →' : 'Open Cupdate →' }}
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
