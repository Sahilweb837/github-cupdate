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
        {"@@type": "ListItem", "position": 2, "name": "{{ addslashes($page['h1']) }}", "item": "{{ url()->current() }}"}
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero section-cream">
    <div class="container narrow">
        <span class="eyebrow-text">HIMACHAL PRADESH GUIDE</span>
        <h1 class="page-title left">{{ $page['h1'] }}</h1>
        <p class="page-subtitle left">{{ $page['intro'] }}</p>
        <div class="hero-actions mt-24">
            <a href="{{ route('register') }}" class="btn btn-pink">Create Free Account</a>
            <a href="{{ route('public.discover', ['city' => $page['city']]) }}" class="btn btn-outline">Browse Local Profiles</a>
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
        <h2 class="section-title">Members {{ $page['city'] ? 'in ' . $page['city'] : 'across Himachal Pradesh' }}</h2>
        <div class="profiles-grid mt-24">
            @foreach ($localMembers as $person)
                <article class="profile-card-public">
                    <div class="profile-card-img-wrap">
                        <img src="{{ $person->avatarUrl() }}" alt="{{ $person->firstName() }} in {{ $person->city }}" loading="lazy">
                    </div>
                    <div class="profile-card-body">
                        <div class="profile-card-top">
                            <h3>{{ $person->firstName() }}@if($person->ageNumber()), {{ $person->ageNumber() }}@endif</h3>
                            <span class="city-tag">{{ $person->city }}</span>
                        </div>
                        <a href="{{ route('register') }}" class="btn btn-pink btn-sm btn-full">Connect on CupDate</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
