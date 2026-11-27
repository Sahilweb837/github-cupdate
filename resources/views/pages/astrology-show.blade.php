@extends('layouts.app')

@section('title', $guide['title'])
@section('description', $guide['description'])
@section('canonical', route('astrology.show', $guide['slug']))

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}"},
        {"@@type": "ListItem", "position": 2, "name": "Love & Astrology", "item": "{{ route('astrology.index') }}"},
        {"@@type": "ListItem", "position": 3, "name": "{{ addslashes($guide['sign_a']['name']) }}", "item": "{{ route('astrology.show', $guide['sign_a']['slug']) }}"},
        {"@@type": "ListItem", "position": 4, "name": "{{ addslashes($guide['h1']) }}", "item": "{{ route('astrology.show', $guide['slug']) }}"}
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach ($guide['faqs'] as $faq)
        {
            "@@type": "Question",
            "name": "{{ addslashes($faq['q']) }}",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "{{ addslashes($faq['a']) }}"
            }
        }{{ $loop->last ? '' : ',' }}
        @endforeach
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <nav class="cup-breadcrumbs" aria-label="Breadcrumb navigation">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('astrology.index') }}">Astrology</a><span>/</span>
            <a href="{{ route('astrology.show', $guide['sign_a']['slug']) }}">{{ $guide['sign_a']['name'] }}</a><span>/</span>
            <a href="{{ route('public.discover') }}">Discover</a><span>/</span>
            <a href="{{ route('cities') }}">Cities</a><span>/</span>
            <span>{{ $guide['h1'] }}</span>
        </nav>

        <span class="eyebrow-pill mt-12">LOVE &amp; COMPATIBILITY · 100% FREE DATING</span>
        <h1 class="page-title left mt-12">{{ $guide['h1'] }}</h1>
        <p class="page-subtitle left">{{ $guide['intro'] }}</p>

        <div class="hero-actions mt-20">
            <a href="{{ route('register') }}" class="btn btn-pink">Join Free &amp; Meet People</a>
            <a href="{{ route('games.truth-dare') }}" class="btn btn-coffee">Play Truth or Dare</a>
            <a href="{{ route('live-video.index') }}" class="btn btn-outline">CupTV Live Video</a>
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container narrow">
        <div class="blog-body-text">
            @foreach ($guide['paragraphs'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>

        <div class="minimal-card mt-24">
            <h2 class="card-title">Suggested First Coffee Date Idea</h2>
            <p class="card-desc mt-8">{{ $guide['coffee_idea'] }}</p>
        </div>

        <div class="mt-24">
            <h2 class="section-title">Compatibility &amp; Dating FAQ</h2>
            <div class="faq-list mt-16" data-faq-accordion>
                @foreach ($guide['faqs'] as $idx => $faq)
                    <div class="faq-item {{ $idx === 0 ? 'is-open' : '' }}">
                        <button type="button" class="faq-question" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
                            <span>{{ $faq['q'] }}</span>
                            <span class="faq-chevron" aria-hidden="true">+</span>
                        </button>
                        <div class="faq-answer"><p>{{ $faq['a'] }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>

        @if (!empty($relatedSigns))
            <div class="mt-24">
                <h2 class="card-title">Explore Related Zodiac Matchups</h2>
                <div class="cities-seo-grid mt-12">
                    @foreach ($relatedSigns as $rel)
                        <a href="{{ route('astrology.show', $rel['slug']) }}" class="city-seo-card">
                            <strong>{{ $rel['h1'] }}</strong>
                            <span>{{ $rel['sign_a']['element'] }}@if($rel['sign_b']) + {{ $rel['sign_b']['element'] }}@endif</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
