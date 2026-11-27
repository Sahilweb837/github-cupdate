@extends('layouts.app')

@section('title', 'Love & Zodiac Astrology Dating Compatibility Guides | CupDate')
@section('description', 'Explore 156+ Zodiac love compatibility guides, star-sign coffee date ideas, and respectful relationship communication tips on CupDate.')

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}"},
        {"@@type": "ListItem", "position": 2, "name": "Discover", "item": "{{ route('public.discover') }}"},
        {"@@type": "ListItem", "position": 3, "name": "Truth & Dare", "item": "{{ route('games.truth-dare') }}"},
        {"@@type": "ListItem", "position": 4, "name": "Cities", "item": "{{ route('cities') }}"},
        {"@@type": "ListItem", "position": 5, "name": "Love & Astrology", "item": "{{ route('astrology.index') }}"}
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero">
    <div class="container">
        <nav class="cup-breadcrumbs" aria-label="Breadcrumb navigation">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('public.discover') }}">Discover</a><span>/</span>
            <a href="{{ route('cities') }}">Cities</a><span>/</span>
            <a href="{{ route('games.truth-dare') }}">Truth &amp; Dare</a><span>/</span>
            <a href="{{ route('travel.groups') }}">Travel Groups</a><span>/</span>
            <span>Love &amp; Astrology</span>
        </nav>
        <span class="eyebrow-pill mt-12">ZODIAC &amp; LOVE COMPATIBILITY · 156+ GUIDES</span>
        <h1 class="page-title left mt-12">Astrology, Zodiac Signs &amp; Coffee Date Compatibility</h1>
        <p class="page-subtitle left">Select a star sign or pair to explore communication style, emotional rhythm, and respectful first coffee date ideas. Free forever on CupDate.</p>
    </div>
</section>

<section class="section-white">
    <div class="container">
        <h2 class="section-title mb-20">All 12 Zodiac Sign Dating Profiles</h2>
        <div class="cities-seo-grid">
            @foreach ($signs as $sign)
                <a href="{{ route('astrology.show', $sign['slug']) }}" class="city-seo-card">
                    <strong>{{ $sign['name'] }} ({{ $sign['element'] }})</strong>
                    <span>{{ $sign['dates'] }} · {{ $sign['trait'] }}</span>
                </a>
            @endforeach
        </div>

        <h2 class="section-title mt-24 mb-12">Popular Love Pairings</h2>
        <div class="cities-seo-grid">
            @foreach ($featuredPairs as $pair)
                <a href="{{ route('astrology.show', $pair['slug']) }}" class="city-seo-card">
                    <strong>{{ $pair['sign_a']['name'] }} &amp; {{ $pair['sign_b']['name'] }}</strong>
                    <span>{{ $pair['sign_a']['element'] }} + {{ $pair['sign_b']['element'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
