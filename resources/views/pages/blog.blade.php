@extends('layouts.app')

@section('title', 'CupDate Journal | Best Himachal, Delhi & India Dating Guides')
@section('description', 'Read CupDate SEO guides for Himachal Pradesh, Delhi NCR, Pune, Haryana, women’s dating safety in India, first-date coffee tips, and MBTI compatibility.')

@section('content')
<section class="page-hero section-cream">
    <div class="container centered narrow">
        <span class="eyebrow-text">CUPDATE BLOG &amp; GUIDES</span>
        <h1 class="page-title">Stories &amp; Guides for Meaningful Meetings</h1>
        <p class="page-subtitle">City guides across Himachal Pradesh, Delhi NCR, Pune &amp; Haryana, plus safe dating advice.</p>
    </div>
</section>

<section class="section-white">
    <div class="container">
        @if ($posts->isNotEmpty())
            <div class="cards-grid-3">
                @foreach ($posts as $post)
                    <article class="profile-card-public">
                        @if ($post->cover_image && file_exists(public_path($post->cover_image)))
                        <div class="profile-card-img-wrap">
                            <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                        </div>
                        @endif
                        <div class="profile-card-body">
                            <h3>{{ $post->title }}</h3>
                            <p class="profile-card-bio">{{ $post->excerpt }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-link">Read article →</a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $posts->links() }}</div>
        @endif

        @if (!empty($seoGuides))
            <div class="mt-24">
                <span class="eyebrow-text">FEATURED INDIA &amp; SAFETY GUIDES</span>
                <h2 class="section-title">Curated Guides for Better Coffee Dates</h2>
                <div class="cards-grid-3 mt-20">
                    @foreach ($seoGuides as $guide)
                        <article class="minimal-card">
                            <span class="tag-soft">{{ $guide['category'] }}</span>
                            <h3 class="card-title mt-8">{{ $guide['h1'] }}</h3>
                            <p class="card-desc">{{ \Illuminate\Support\Str::limit($guide['description'], 125) }}</p>
                            <a href="{{ route('blog.show', $guide['slug']) }}" class="text-link mt-12">Read full guide →</a>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
