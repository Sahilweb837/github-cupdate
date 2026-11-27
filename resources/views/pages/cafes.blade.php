@extends('layouts.app')

@section('title', 'Best Dating Cafés & Member Café Reviews | Safe & Women-Friendly | CupDate')
@section('description', 'Read honest café reviews by girls and members across India & worldwide, discover women-friendly coffee date spots, and share your own café review on CupDate.')

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}"},
        {"@@type": "ListItem", "position": 2, "name": "Café Reviews", "item": "{{ route('cafes.index') }}"}
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero section-cream">
    <div class="container">
        <span class="eyebrow-pill"><x-icon name="coffee" class="cup-icon-xs" /> VERIFIED CAFÉ REVIEWS</span>
        <h1 class="page-title left mt-12">Women-Friendly Café Reviews &amp; Date Spots</h1>
        <p class="page-subtitle left">Girls and members review real cafés for safety, daylight seating, ambience, and coffee quality - and share top spots on social media.</p>
    </div>
</section>

<section class="section-white">
    <div class="container moments-layout">
        <div class="moments-composer-card">
            <h2 class="card-title">Write a Café Review</h2>
            <p class="muted-small mt-8">Help other members pick safe, comfortable coffee spots. Screened against spam &amp; abuse.</p>

            @if (session('success'))
                <div class="alert-success mt-12">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-error mt-12">{{ $errors->first() }}</div>
            @endif

            @auth
                <form method="POST" action="{{ route('cafes.store') }}" class="cupdate-form mt-16">
                    @csrf
                    <div class="form-group">
                        <label for="cafe_name">Café Name</label>
                        <input id="cafe_name" name="cafe_name" type="text" list="cafePlaceList" value="{{ old('cafe_name') }}" placeholder="e.g. Illiterati Books & Cafe" required>
                        <datalist id="cafePlaceList">
                            @foreach ($datePlaces as $place)
                                <option value="{{ $place->name }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="cafe_city">City</label>
                            <input id="cafe_city" name="city" type="text" value="{{ old('city', auth()->user()->city) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="cafe_rating">Rating</label>
                            <select id="cafe_rating" name="rating">
                                <option value="5">5/5 (5/5)</option>
                                <option value="4">4/5 (4/5)</option>
                                <option value="3">3/5 (3/5)</option>
                                <option value="2">2/5 (2/5)</option>
                                <option value="1">1/5 (1/5)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="safety_tag">Safety &amp; Vibe Tag</label>
                        <select id="safety_tag" name="safety_tag">
                            <option value="Women-Friendly & Safe">Women-Friendly &amp; Safe</option>
                            <option value="Bright Daylight Seating">Bright Daylight Seating</option>
                            <option value="Quiet First-Date Vibe">Quiet First-Date Vibe</option>
                            <option value="Great Coffee & Service">Great Coffee &amp; Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review_text">Your Honest Review</label>
                        <textarea id="review_text" name="review_text" rows="3" placeholder="Share seating comfort, staff friendliness, lighting, and coffee notes..." required>{{ old('review_text') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-pink btn-full">Publish Café Review</button>
                </form>
            @else
                <div class="mt-16">
                    <p class="muted-small">Join CupDate free forever to review cafés and help keep meetups safe.</p>
                    <a href="{{ route('register') }}" class="btn btn-pink btn-full mt-12">Join Free to Review</a>
                </div>
            @endauth
        </div>

        <div class="moments-feed-col">
            <div class="filter-bar mb-16">
                <a href="{{ route('cafes.index') }}" class="filter-chip {{ empty($activeTag) ? 'is-active' : '' }}">All Reviews</a>
                @foreach (['Women-Friendly & Safe', 'Bright Daylight Seating', 'Quiet First-Date Vibe', 'Great Coffee & Service'] as $tagOpt)
                    <a href="{{ route('cafes.index', ['tag' => $tagOpt]) }}" class="filter-chip {{ ($activeTag ?? '') === $tagOpt ? 'is-active' : '' }}">{{ $tagOpt }}</a>
                @endforeach
            </div>

            <div class="cafe-places-stack mb-20">
                @foreach ($datePlaces as $place)
                    <div class="cafe-spot-card">
                        <div class="cafe-spot-icon"><x-icon name="coffee" class="cup-icon-sm" /></div>
                        <div>
                            <div class="cafe-spot-name">{{ $place->name }}</div>
                            <div class="cafe-spot-address">{{ $place->address }}</div>
                        </div>
                        <span class="cafe-rating">Rating {{ number_format((float) $place->rating, 1) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="skeleton-grid-wrap" data-skeleton-container>
                <div class="skeleton-overlay skeleton-overlay-2" data-skeleton-loader aria-hidden="true">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="skeleton-card">
                            <div class="skeleton-box skeleton-line w-60"></div>
                            <div class="skeleton-box skeleton-line w-85"></div>
                        </div>
                    @endfor
                </div>

                <div class="moments-grid" data-skeleton-content>
                @forelse ($reviews as $review)
                    @php
                        $shareText = "{$review->cafe_name} ({$review->city}) - {$review->rating}/5: " . \Illuminate\Support\Str::limit($review->review_text, 90) . ' via CupDate';
                        $shareUrl = route('cafes.index', ['city' => $review->city]);
                    @endphp
                    <article class="moment-card">
                        <div class="moment-head">
                            <x-avatar :user="$review->user" :alt="$review->user?->firstName() ?? 'Member'" class="thread-avatar" />
                            <div>
                                <strong>{{ $review->cafe_name }}</strong>
                                <div class="muted-small">{{ $review->city }} · By {{ $review->user?->firstName() ?? 'Member' }} · Rating: {{ $review->rating }}/5</div>
                            </div>
                        </div>
                        <span class="tag-soft">{{ $review->safety_tag }}</span>
                        <p class="moment-body">{{ $review->review_text }}</p>
                        <div class="social-share-bar" data-share-endpoint="{{ route('cafes.share', $review) }}">
                            <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">WhatsApp</a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-pill js-share-track">X</a>
                            <button type="button" class="share-pill js-native-share" data-title="{{ $review->cafe_name }} Review" data-text="{{ $shareText }}" data-url="{{ $shareUrl }}">Share</button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state-card">
                        <h3>No member café reviews yet</h3>
                        <p class="muted-small">Be the first to review a women-friendly coffee date spot!</p>
                    </div>
                @endforelse
                </div>
            </div>

            @if (method_exists($reviews, 'links'))
                <div class="pagination-wrap">{{ $reviews->links() }}</div>
            @endif
        </div>
    </div>
</section>
@endsection
