@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title . ' - CupDate')
@section('description', $post->meta_description ?: $post->excerpt)
@section('og_type', 'article')
@section('og_image', asset($post->cover_image ?: 'images/cafe-blue-bottle.jpg'))

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Article",
    "headline": "{{ addslashes($post->title) }}",
    "description": "{{ addslashes($post->meta_description ?: $post->excerpt) }}",
    "image": "{{ asset($post->cover_image ?: 'images/cafe-blue-bottle.jpg') }}",
    "datePublished": "{{ ($post->published_at ?? $post->created_at)?->toAtomString() }}",
    "dateModified": "{{ ($post->updated_at ?? $post->published_at ?? $post->created_at)?->toAtomString() }}",
    "publisher": {
        "@@type": "Organization",
        "name": "CupDate",
        "url": "https://cupdate.in"
    }
}
</script>
@endpush

@section('content')
<article class="page-hero section-white">
    <div class="container narrow">
        <a href="{{ route('blog') }}" class="text-link">← Back to Blog</a>
        <h1 class="page-title left mt-16">{{ $post->title }}</h1>
        <p class="muted-small">Published {{ $post->published_at?->format('M j, Y') }}</p>
        <img src="{{ asset($post->cover_image ?: 'images/cafe-blue-bottle.jpg') }}" alt="{{ $post->title }}" class="blog-cover-img">
        <div class="blog-body-text">
            {!! nl2br(e($post->body)) !!}
        </div>
    </div>
</article>
@endsection
