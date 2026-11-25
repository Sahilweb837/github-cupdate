@extends('layouts.app')

@section('title', 'About — Cupdate')

@section('content')
<section class="page-hero">
    <div class="container centered narrow">
        <span class="eyebrow-text">OUR STORY</span>
        <h1 class="page-title">We believe connection should feel like a good cup of coffee.</h1>
        <p class="page-subtitle">
            Warm, unhurried, and better shared. Cupdate was founded in 2021 after three friends got tired of apps built to keep you swiping instead of meeting.
        </p>
    </div>
</section>

<section class="section-compact">
    <div class="container cards-grid-3">
        @foreach ($metrics as $metric)
            <div class="metric-card">
                <div class="metric-value">{{ $metric['value'] }}</div>
                <div class="metric-label">{{ $metric['label'] }}</div>
            </div>
        @endforeach
    </div>
</section>

<section class="plum-values-band">
    <div class="container">
        <h2 class="values-heading">What we stand for</h2>
        <div class="values-grid">
            @foreach ($values as $value)
                <div class="value-item">
                    <div class="value-icon" aria-hidden="true">
                        @if ($value['icon'] === 'user')
                            ●
                        @elseif ($value['icon'] === 'heart')
                            ❤
                        @else
                            ☕
                        @endif
                    </div>
                    <h3 class="value-title">{{ $value['title'] }}</h3>
                    <p class="value-desc">{{ $value['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading centered">
            <h2 class="section-title">The people behind Cupdate</h2>
        </div>

        <div class="team-grid">
            @foreach ($team as $member)
                <article class="team-card">
                    <div class="team-photo-wrap">
                        <img src="{{ asset($member['photo']) }}" alt="{{ $member['name'] }}" class="team-photo">
                    </div>
                    <h3 class="team-name">{{ $member['name'] }}</h3>
                    <p class="team-role">{{ $member['role'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
