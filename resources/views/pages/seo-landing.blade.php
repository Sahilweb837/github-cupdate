@extends('layouts.app')

@section('title', $page['title'])
@section('description', $page['description'])

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}"},
        {"@@type": "ListItem", "position": 2, "name": "All Cities", "item": "{{ route('cities') }}"},
        {"@@type": "ListItem", "position": 3, "name": "{{ addslashes($page['h1']) }}", "item": "{{ url()->current() }}"}
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        {
            "@@type": "Question",
            "name": "Is {{ addslashes($page['city'] ?? 'CupDate') }} coffee dating really 100% free?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Yes. Messaging, discovering verified singles, OmeTV-style live video, and sharing coffee moments are 100% free on CupDate without paid subscriptions."
            }
        },
        {
            "@@type": "Question",
            "name": "How does CupDate keep local dating safe from nudity and spam?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Every upload, bio, and message is screened against nudity and spam with instant report/block controls and human moderation."
            }
        }
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero section-cream">
    <div class="container narrow">
        <span class="eyebrow-pill">{{ strtoupper($page['country'] ?? 'INDIA') }} · 100% FREE FOREVER</span>
        <h1 class="page-title left mt-12">{{ $page['h1'] }}</h1>
        <p class="page-subtitle left">{{ $page['intro'] }}</p>
        <div class="hero-actions mt-24">
            <a href="{{ route('register') }}" class="btn btn-pink">Join Now Free</a>
            <a href="{{ route('live-video.index') }}" class="btn btn-coffee">Try Live Video (OmeTV)</a>
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

@if ($localMembers->isNotEmpty())
<section class="section-grey">
    <div class="container">
        <h2 class="section-title">Members {{ $page['city'] ? 'in ' . $page['city'] : 'across ' . ($page['country'] ?? 'India') }}</h2>
        <div class="profiles-grid mt-24">
            @foreach ($localMembers as $person)
                <article class="profile-card-public">
                    <div class="profile-card-img-wrap">
                        <img src="{{ $person->avatarUrl() }}" alt="{{ $person->firstName() }} in {{ $person->city }}" loading="lazy" decoding="async">
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
