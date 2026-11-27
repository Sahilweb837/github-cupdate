@extends('layouts.app')

@section('title', $page['title'])
@section('description', $page['description'])
@if (!empty($canonicalUrl))
@section('canonical', $canonicalUrl)
@endif

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}"},
        {"@@type": "ListItem", "position": 2, "name": "Discover", "item": "{{ route('public.discover') }}"},
        {"@@type": "ListItem", "position": 3, "name": "All Cities", "item": "{{ route('cities') }}"},
        {"@@type": "ListItem", "position": 4, "name": "{{ addslashes($page['country'] ?? 'India') }}", "item": "{{ route('cities') }}"},
        {"@@type": "ListItem", "position": 5, "name": "{{ addslashes($page['state'] ?? 'Region') }}", "item": "{{ route('cities') }}"},
        {"@@type": "ListItem", "position": 6, "name": "{{ addslashes($page['h1']) }}", "item": "{{ $canonicalUrl ?? url()->current() }}"}
    ]
}
</script>
@php $cityFaqs = $page['city_faqs'] ?? [
    ['q' => 'Is CupDate coffee dating 100% free?', 'a' => 'Yes. Messaging, discovering verified singles, CupTV live video, and sharing coffee moments are 100% free without paid subscriptions.'],
    ['q' => 'How does CupDate keep local dating safe?', 'a' => 'Every upload, bio, and message is screened against nudity and spam with instant report/block controls and human moderation.'],
]; @endphp
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach ($cityFaqs as $faq)
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
            <a href="{{ route('public.discover') }}">Discover</a><span>/</span>
            <a href="{{ route('cities') }}">{{ $page['country'] ?? 'India' }}</a><span>/</span>
            <a href="{{ route('cities') }}">{{ $page['state'] ?? 'All Cities' }}</a><span>/</span>
            <a href="{{ route('cafes.index') }}">Cafés</a><span>/</span>
            <a href="{{ route('astrology.index') }}">Love &amp; Astrology</a><span>/</span>
            <span>{{ $page['city'] ?? ($page['country'] ?? 'City Guide') }}</span>
        </nav>

        <span class="eyebrow-pill mt-12">{{ strtoupper($page['country'] ?? 'INDIA') }} · 100% FREE FOREVER</span>
        <h1 class="page-title left mt-12">{{ $page['h1'] }}</h1>
        <p class="page-subtitle left">{{ $page['intro'] }}</p>
        <div class="hero-actions mt-24">
            <a href="{{ route('register') }}" class="btn btn-pink">Join Now Free</a>
            <a href="{{ route('live-video.index') }}" class="btn btn-coffee">Try CupTV Free Video</a>
            <a href="{{ route('public.discover', ['city' => $page['city']]) }}" class="btn btn-outline">Browse Profiles</a>
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container cards-grid-2">
        <div class="minimal-card">
            <h2 class="card-title">Why connect on CupDate</h2>
            <ul class="clean-list">
                @foreach ($page['highlights'] as $item)
                    <li><x-icon name="check" class="cup-icon-xs" /> {{ $item }}</li>
                @endforeach
            </ul>
        </div>
        <div class="minimal-card">
            <h2 class="card-title">Thoughtful coffee date ideas</h2>
            <ul class="clean-list">
                @foreach ($page['ideas'] as $idea)
                    <li><x-icon name="coffee" class="cup-icon-xs" /> {{ $idea }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

<section class="section-cream">
    <div class="container">
        <div class="honest-story-card" style="margin-top:0;">
            <span class="eyebrow-text">LOCAL DATING CULTURE &amp; FAMOUS COFFEE HUBS</span>
            <h2 class="card-title mt-8">How CupDate Works in {{ $page['city'] ?? ($page['country'] ?? 'Your City') }}</h2>
            <p class="mt-8">{{ $page['city_culture'] ?? ('Create your free profile in under a minute, browse verified 18+ members in ' . ($page['city'] ?? 'your city') . ', start 1-to-1 encrypted conversations, or try a quick CupTV live video hello before meeting at a public daytime cafe.') }}</p>
            <div class="hero-actions">
                <a href="{{ route('games.truth-dare') }}" class="btn btn-coffee btn-sm">Play Truth or Dare</a>
                <a href="{{ route('travel.groups') }}" class="btn btn-outline btn-sm">Join Travel Groups</a>
                <a href="{{ route('cafes.index') }}" class="btn btn-outline btn-sm">Read Cafe Reviews</a>
                <a href="{{ route('astrology.index') }}" class="btn btn-blush btn-sm">Love &amp; Astrology Guides</a>
            </div>
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container narrow">
        <span class="eyebrow-text">{{ strtoupper($page['city'] ?? 'LOCAL') }} FAQ</span>
        <h2 class="section-title">Frequently Asked Questions About Dating in {{ $page['city'] ?? ($page['country'] ?? 'Your City') }}</h2>
        <div class="faq-list mt-16" data-faq-accordion>
            @foreach ($cityFaqs as $idx => $faq)
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
</section>

@if ($localMembers->isNotEmpty())
<section class="section-grey">
    <div class="container">
        <h2 class="section-title">Members {{ $page['city'] ? 'in ' . $page['city'] : 'across ' . ($page['country'] ?? 'India') }}</h2>
        <div class="profiles-grid mt-24">
            @foreach ($localMembers as $person)
                <article class="profile-card-public">
                    <div class="profile-card-img-wrap">
                        <x-avatar :user="$person" :alt="$person->firstName() . ' in ' . $person->city" />
                    </div>
                    <div class="profile-card-body">
                        <div class="profile-card-top">
                            <h3>{{ $person->firstName() }}@if($person->ageNumber()), {{ $person->ageNumber() }}@endif</h3>
                            <span class="city-tag">{{ $person->city }}</span>
                        </div>
                        <a href="{{ route('register') }}" class="btn btn-pink btn-sm btn-full">Connect Free</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if (!empty($relatedCities))
<section class="section-white">
    <div class="container">
        <div class="discover-header-row">
            <h2 class="section-title">Explore More Cities &amp; Countries</h2>
            <a href="{{ route('cities') }}" class="text-link">All 100+ Cities →</a>
        </div>
        <div class="cities-seo-grid mt-20">
            @foreach ($relatedCities as $rel)
                <a href="{{ route('seo.city', $rel['slug']) }}" class="city-seo-card">
                    <strong>Dating in {{ $rel['name'] }}</strong>
                    <span>{{ $rel['state'] }}, {{ $rel['country'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
