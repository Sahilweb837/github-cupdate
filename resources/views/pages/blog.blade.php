@extends('layouts.app')

@section('title', 'CupDate Journal — Coffee Date Guides & Safety Tips')
@section('description', 'Read CupDate guides on coffee date spots in Himachal Pradesh, building genuine profiles, and safe dating habits.')

@section('content')
<section class="page-hero section-cream">
    <div class="container centered narrow">
        <span class="eyebrow-text">CUPDATE BLOG</span>
        <h1 class="page-title">Stories &amp; Guides for Meaningful Meetings</h1>
        <p class="page-subtitle">Practical café guides across Himachal Pradesh and advice on creating authentic connections.</p>
    </div>
</section>

<section class="section-white">
    <div class="container cards-grid-3">
        @foreach ($posts as $post)
            <article class="profile-card-public">
                <div class="profile-card-img-wrap">
                    <img src="{{ asset($post->cover_image ?: 'images/cafe-blue-bottle.jpg') }}" alt="{{ $post->title }}" loading="lazy">
                </div>
                <div class="profile-card-body">
                    <h3>{{ $post->title }}</h3>
                    <p class="profile-card-bio">{{ $post->excerpt }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-link">Read article →</a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
