@extends('layouts.dashboard')

@section('title', 'Discover — Cupdate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">TODAY’S PICKS</span>
        <h1 class="app-page-title">Good morning, {{ $user['name'] }}</h1>
    </div>
    <div class="app-status-pill">
        <span class="status-dot-live"></span> 94% profile synced today
    </div>
</div>

<div class="discover-grid">
    <div class="picks-column">
        <div class="picks-grid" data-picks-list>
            @foreach ($picks as $pick)
                <article class="pick-card" data-pick-card>
                    <div class="pick-photo-wrap">
                        <img src="{{ asset($pick['photo']) }}" alt="{{ $pick['name'] }}" class="pick-photo">
                        <span class="pick-match-pill">{{ $pick['match'] }}% match</span>
                    </div>
                    <div class="pick-body">
                        <h2 class="pick-name">{{ $pick['name'] }}</h2>
                        <p class="pick-city">{{ $pick['city'] }}</p>
                        <p class="pick-prompt">“{{ $pick['prompt'] }}”</p>
                        <div class="pick-tags">
                            @foreach ($pick['tags'] as $tag)
                                <span class="tag-chip">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <div class="pick-actions">
                            <button type="button" class="btn btn-outline btn-sm" data-pick-action="pass">Pass</button>
                            <button type="button" class="btn btn-coral btn-sm" data-pick-action="like">❤ Say hi</button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    <aside class="app-aside-stack">
        <div class="plum-mini-card">
            <span class="eyebrow-text light">LIVE RADAR</span>
            <h3>4 compatible people within 800m</h3>
            <p>Check who’s at a café nearby right now.</p>
            <a href="{{ route('app.radar') }}" class="btn btn-coral btn-sm">Open Radar →</a>
        </div>

        <div class="blush-mini-card">
            <h3>Next coffee date</h3>
            <p><strong>Diego, 29</strong> · Blue Bottle Coffee</p>
            <p class="muted-small">Sat, Nov 28 · 4:00 PM · Confirmed</p>
            <a href="{{ route('app.dates') }}" class="text-link">View schedule →</a>
        </div>
    </aside>
</div>
@endsection
