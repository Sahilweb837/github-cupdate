@extends('layouts.app')

@section('title', $guide['title'])
@section('description', $guide['description'])
@section('og_type', 'article')
@section('og_image', asset('images/cupdate-scene.svg'))
@if (!empty($canonicalUrl))
@section('canonical', $canonicalUrl)
@endif

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Article",
    "headline": "{{ addslashes($guide['h1']) }}",
    "description": "{{ addslashes($guide['description']) }}",
    "image": "{{ asset('images/cupdate-scene.svg') }}",
    "author": {
        "@@type": "Organization",
        "name": "CupDate Editorial Team"
    },
    "publisher": {
        "@@type": "Organization",
        "name": "CupDate",
        "url": "https://cupdate.in",
        "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('images/cupdate-logo.svg') }}"
        }
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}"},
        {"@@type": "ListItem", "position": 2, "name": "Guides & Blog", "item": "{{ route('blog') }}"},
        {"@@type": "ListItem", "position": 3, "name": "{{ addslashes($guide['h1']) }}", "item": "{{ url()->current() }}"}
    ]
}
</script>
@if (!empty($guide['faqs']))
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
@endif
@endpush

@section('content')
<article class="page-hero section-cream">
    <div class="container narrow">
        <span class="eyebrow-pill">{{ strtoupper($guide['category']) }} · 100% FREE FOREVER</span>
        <h1 class="page-title left mt-12">{{ $guide['h1'] }}</h1>
        <p class="page-subtitle left">{{ $guide['description'] }}</p>
        <div class="hero-actions mt-20">
            <a href="{{ route('register') }}" class="btn btn-pink">Join Now Free</a>
            <a href="{{ route('live-video.index') }}" class="btn btn-coffee">Try CupTV Video</a>
            <a href="{{ route('cafes.index') }}" class="btn btn-outline">Café Reviews</a>
        </div>
    </div>
</article>

<section class="section-white">
    <div class="container narrow">
        <div class="blog-body-text">
            @foreach ($guide['paragraphs'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>

        @if (!empty($guide['checklist']))
            <div class="minimal-card mt-24">
                <h2 class="card-title">Key Highlights &amp; Safety Checklist</h2>
                <ul class="clean-list">
                    @foreach ($guide['checklist'] as $item)
                        <li><x-icon name="check" class="cup-icon-xs" /> {{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!empty($guide['faqs']))
            <div class="mt-24">
                <h2 class="section-title">Frequently Asked Questions</h2>
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
        @endif

        @if (!empty($relatedGuides))
            <div class="mt-24">
                <h2 class="card-title">More Popular CupDate Guides</h2>
                <div class="cities-seo-grid mt-12">
                    @foreach ($relatedGuides as $rel)
                        <a href="{{ route('blog.show', $rel['slug']) }}" class="city-seo-card">
                            <strong>{{ $rel['h1'] }}</strong>
                            <span>{{ $rel['category'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
