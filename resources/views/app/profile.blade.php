@extends('layouts.dashboard')

@section('title', 'Profile — Cupdate')

@section('content')
<div class="profile-banner">
    <img src="{{ asset($user['avatar']) }}" alt="{{ $user['name'] }}" class="profile-banner-avatar">
    <div>
        <h1 class="profile-banner-name">{{ $user['name'] }}, 27</h1>
        <p class="profile-banner-city">{{ $user['city'] }} · Verified member ✓</p>
    </div>
</div>

<div class="profile-grid">
    <div class="profile-card">
        <h2 class="panel-heading">Live prompts &amp; bio</h2>
        <form class="cupdate-form" data-profile-form>
            <div class="form-group">
                <label for="profileCity">Neighborhood</label>
                <input id="profileCity" type="text" value="{{ $user['city'] }}">
            </div>
            <div class="form-group">
                <label for="profilePrompt">Coffee order &amp; Sunday vibe</label>
                <textarea id="profilePrompt" rows="3">Oat flat white, farmers market strolls, and spontaneous bookstore afternoons.</textarea>
            </div>
            <div class="toggle-row">
                <span>Show me on Live Radar (800m café radius)</span>
                <input type="checkbox" checked>
            </div>
            <button type="submit" class="btn btn-coral">Save profile updates</button>
            <span class="save-feedback" data-profile-saved hidden>Saved! Your match scores are refreshing.</span>
        </form>
    </div>

    <div class="profile-card">
        <h2 class="panel-heading">Interests &amp; chemistry</h2>
        <div class="pick-tags">
            <span class="tag-chip">Specialty coffee</span>
            <span class="tag-chip">Architecture</span>
            <span class="tag-chip">Indie cinema</span>
            <span class="tag-chip">Weekend hikes</span>
            <span class="tag-chip">Vinyl records</span>
        </div>
    </div>
</div>
@endsection
