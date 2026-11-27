@extends('layouts.app')

@section('title', 'Coffee Moments | Share Stories & Photos on Social Media | CupDate')
@section('description', 'Share verified coffee dates, mountain cafe stories, and moments on CupDate and across WhatsApp, Instagram, X, and Facebook with zero nudity or spam.')

@section('content')
<section class="page-hero section-cream">
    <div class="container">
        <span class="eyebrow-pill"><x-icon name="heart" class="cup-icon-xs" /> SAFE &amp; VERIFIED MOMENTS</span>
        <h1 class="page-title left mt-12">Share Coffee Moments With the World</h1>
        <p class="page-subtitle left">Post your café stories, mountain views, and safe meetup moments - and share them directly to WhatsApp, Instagram, X, or Facebook. Every post is screened against nudity and spam.</p>
    </div>
</section>

<section class="section-white">
    <div class="container moments-layout">
        <div class="moments-composer-card">
            <h2 class="card-title">Post a Coffee Moment</h2>
            <p class="muted-small mt-8">100% free forever. Automatic anti-nudity &amp; anti-spam safety check enabled.</p>

            @if (session('success'))
                <div class="alert-success mt-12">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-error mt-12">{{ $errors->first() }}</div>
            @endif

            @auth
                <form method="POST" action="{{ route('moments.store') }}" enctype="multipart/form-data" class="cupdate-form mt-16">
                    @csrf
                    <div class="form-group">
                        <label for="momentContent">Your Moment</label>
                        <textarea id="momentContent" name="content" rows="3" placeholder="Enjoying an afternoon cappuccino in Dharamshala..." required>{{ old('content') }}</textarea>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="momentCity">City</label>
                            <input id="momentCity" name="city" type="text" value="{{ old('city', auth()->user()->city) }}" placeholder="e.g. Dharamshala, Bengaluru">
                        </div>
                        <div class="form-group">
                            <label for="momentMood">Vibe / Tag</label>
                            <select id="momentMood" name="mood">
                                <option value="Coffee Vibes">Coffee Vibes</option>
                                <option value="First Meetup">First Meetup</option>
                                <option value="Girls Coffee Club">Girls Coffee Club</option>
                                <option value="Mountain Cafe">Mountain Cafe</option>
                                <option value="City Sunset">City Sunset</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="momentImage">Photo (optional, JPEG/PNG/WEBP, max 4MB)</label>
                        <input id="momentImage" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                    </div>
                    <button type="submit" class="btn btn-pink btn-full">Share Moment Safely</button>
                </form>
            @else
                <div class="mt-16">
                    <p class="muted-small">Create a free account in seconds to share your coffee moments safely.</p>
                    <a href="{{ route('register') }}" class="btn btn-pink btn-full mt-12">Join Now - 100% Free</a>
                </div>
            @endauth
        </div>

        <div class="moments-feed-col">
            <div class="filter-bar mb-16" role="region" aria-label="Filter Moments by Vibe">
                <a href="{{ route('moments.index') }}" class="filter-chip {{ empty($activeMood) ? 'is-active' : '' }}">All Moments</a>
                @foreach (['Coffee Vibes', 'Girls Coffee Club', 'First Meetup', 'Mountain Cafe', 'City Sunset'] as $moodOpt)
                    <a href="{{ route('moments.index', ['mood' => $moodOpt]) }}" class="filter-chip {{ ($activeMood ?? '') === $moodOpt ? 'is-active' : '' }}">{{ $moodOpt }}</a>
                @endforeach
            </div>

            <div class="skeleton-grid-wrap" data-skeleton-container>
                <div class="skeleton-overlay skeleton-overlay-2" data-skeleton-loader aria-hidden="true">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="skeleton-card">
                            <div class="skeleton-box skeleton-img"></div>
                            <div class="skeleton-box skeleton-line w-60"></div>
                            <div class="skeleton-box skeleton-line w-85"></div>
                        </div>
                    @endfor
                </div>

                <div class="moments-grid" data-skeleton-content>
                @forelse ($moments as $moment)
                    @php
                        $shareUrl = route('moments.show', $moment);
                        $shareText = \Illuminate\Support\Str::limit($moment->content, 110) . ' - via CupDate (cupdate.in)';
                    @endphp
                    <article class="moment-card">
                        <div class="moment-head">
                            <x-avatar :user="$moment->user" :alt="$moment->user?->firstName() ?? 'Member'" class="thread-avatar" />
                            <div>
                                <strong>{{ $moment->user?->firstName() ?? 'CupDate Member' }}</strong>
                                <div class="muted-small">
                                    {{ $moment->city ?? $moment->user?->city ?? 'India' }}
                                    @if ($moment->mood) · {{ $moment->mood }} @endif
                                </div>
                            </div>
                        </div>

                        @if ($moment->image_url)
                            <a href="{{ $shareUrl }}" class="moment-img-wrap">
                                <img src="{{ asset($moment->image_url) }}" alt="Coffee moment in {{ $moment->city ?? 'India' }}" loading="lazy" decoding="async">
                            </a>
                        @endif

                        <p class="moment-body">{{ $moment->content }}</p>

                        <div class="social-share-bar" data-share-endpoint="{{ route('moments.share', $moment) }}">
                            <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">WhatsApp</a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">X / Twitter</a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">Facebook</a>
                            <button type="button" class="share-pill js-native-share" data-title="CupDate Moment" data-text="{{ $shareText }}" data-url="{{ $shareUrl }}">Share / Copy Link</button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state-card">
                        <h3>No moments shared yet</h3>
                        <p class="muted-small">Be the first to share a safe coffee moment and invite friends from social media!</p>
                    </div>
                @endforelse
                </div>
            </div>

            <div class="pagination-wrap">{{ $moments->links() }}</div>
        </div>
    </div>
</section>
@endsection
