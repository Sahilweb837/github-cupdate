@extends('layouts.app')

@section('title', ($moment->user?->firstName() ?? 'CupDate') . '’s Coffee Moment' . ($moment->city ? ' in ' . $moment->city : '') . ' | CupDate')
@section('description', \Illuminate\Support\Str::limit($moment->content, 150))
@section('og_type', 'article')
@section('og_image', $moment->image_url ? asset($moment->image_url) : asset('images/hero-coffee-couple.jpg'))

@section('content')
<section class="page-hero section-cream">
    <div class="container narrow">
        <a href="{{ route('moments.index') }}" class="text-link">← All Coffee Moments</a>
        <h1 class="page-title left mt-12">Coffee Moment by {{ $moment->user?->firstName() ?? 'Member' }}</h1>
        <p class="muted-small">{{ $moment->city ?? 'India' }} · {{ $moment->mood ?? 'Coffee Vibes' }} · {{ $moment->created_at?->diffForHumans() }}</p>
    </div>
</section>

<section class="section-white">
    <div class="container narrow">
        <article class="moment-card">
            @if ($moment->image_url)
                <div class="moment-img-wrap">
                    <img src="{{ asset($moment->image_url) }}" alt="Coffee moment">
                </div>
            @endif
            <p class="blog-body-text mt-16">{{ $moment->content }}</p>

            @php
                $shareUrl = route('moments.show', $moment);
                $shareText = \Illuminate\Support\Str::limit($moment->content, 110) . ' — CupDate (cupdate.in)';
            @endphp
            <div class="social-share-bar mt-16" data-share-endpoint="{{ route('moments.share', $moment) }}">
                <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">Share on WhatsApp</a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">Share on X</a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">Share on Facebook</a>
                <button type="button" class="share-pill js-native-share" data-title="CupDate Moment" data-text="{{ $shareText }}" data-url="{{ $shareUrl }}">Copy / Share Link</button>
            </div>
        </article>
    </div>
</section>
@endsection
