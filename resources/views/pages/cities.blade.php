@extends('layouts.app')

@section('title', 'Free Coffee Dating in ' . $totalCities . '+ Indian Cities & Global Hubs | CupDate')
@section('description', 'Explore CupDate free coffee dating, CupTV live video chat, and verified connections across ' . $totalCities . '+ Indian cities and global countries — 100% free forever.')

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}"},
        {"@@type": "ListItem", "position": 2, "name": "100+ Cities & Countries", "item": "{{ route('cities') }}"}
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero section-cream">
    <div class="container">
        <span class="eyebrow-pill">ALL INDIA &amp; WORLDWIDE · {{ $totalCities }}+ PAGES</span>
        <h1 class="page-title left mt-12">Free Coffee Dating Across {{ $totalCities }}+ Indian Cities &amp; the World</h1>
        <p class="page-subtitle left">Every city page features verified local singles, coffee date ideas, CupTV free live video, and 100% free messaging without paid paywalls.</p>
        <div class="mt-20">
            <input type="search" id="cityDirectorySearch" class="city-search-input" placeholder="Search any city or state (e.g. Delhi, Mumbai, Shimla, Bengaluru, Dubai)...">
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container">
        <div class="cities-groups-stack" id="cityDirectoryGroups">
            @foreach ($groups as $groupName => $items)
                <div class="city-group-block" data-city-group>
                    <h2 class="city-group-title">{{ $groupName }}</h2>
                    <div class="cities-seo-grid">
                        @foreach ($items as $item)
                            <a href="{{ route('seo.city', $item['slug']) }}" class="city-seo-card" data-city-item="{{ strtolower($item['name'] . ' ' . $item['state'] . ' ' . $item['country']) }}">
                                <strong>Free Dating in {{ $item['name'] }}</strong>
                                <span>{{ $item['spot'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
