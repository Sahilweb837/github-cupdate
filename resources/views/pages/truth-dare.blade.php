@extends('layouts.app')

@section('title', 'Truth or Dare Online Game & Coffee Date Icebreakers | CupDate')
@section('description', 'Play free Truth or Dare online with coffee date questions, clean couple challenges, and fun CupTV live video icebreakers on CupDate.')

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        {
            "@@type": "Question",
            "name": "Can we play Truth or Dare online during a coffee date or CupTV call?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Yes. Choose Truth, Dare, or Random Card across Coffee Date, Couples, Friends, or CupTV Video modes to break the ice naturally."
            }
        },
        {
            "@@type": "Question",
            "name": "Are CupDate Truth or Dare questions clean and respectful?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Every card is curated for genuine conversation, humor, and comfortable daytime dating without explicit content."
            }
        }
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero section-cream">
    <div class="container">
        <span class="eyebrow-pill"><x-icon name="sparkles" class="cup-icon-xs" /> FREE ICEBREAKER GAME</span>
        <h1 class="page-title left mt-12">Truth or Dare for Coffee Dates &amp; Live Video</h1>
        <p class="page-subtitle left">Pick a category below and tap Truth, Dare, or Spin Random to spark fun, honest conversations on a café meetup, travel trip, or CupTV live video call.</p>
    </div>
</section>

<section class="section-white">
    <div class="container">
        <div class="td-shell" data-truth-dare-app>
            <div class="filter-bar mb-20" role="group" aria-label="Select Truth or Dare Category">
                <button type="button" class="filter-chip is-active" data-td-category="all">All Categories</button>
                <button type="button" class="filter-chip" data-td-category="coffee">Coffee Date</button>
                <button type="button" class="filter-chip" data-td-category="couple">Couples</button>
                <button type="button" class="filter-chip" data-td-category="friends">Friends &amp; Travel</button>
                <button type="button" class="filter-chip" data-td-category="video">CupTV Video</button>
            </div>

            <div class="td-card-stage">
                <article class="td-prompt-card" id="tdPromptCard" aria-live="polite">
                    <span class="td-mode-badge" id="tdModeLabel">TRUTH OR DARE</span>
                    <h2 class="td-prompt-text" id="tdPromptText">Tap Truth or Dare below to draw your first icebreaker card.</h2>
                    <p class="muted-small" id="tdCategoryHint">Safe, thoughtful prompts built for real conversations.</p>
                </article>

                <div class="td-action-row">
                    <button type="button" class="btn btn-pink" id="tdTruthBtn">Draw Truth</button>
                    <button type="button" class="btn btn-coffee" id="tdDareBtn">Draw Dare</button>
                    <button type="button" class="btn btn-outline" id="tdRandomBtn">Random Card</button>
                    <button type="button" class="btn btn-blush" id="tdCopyBtn">Copy Prompt</button>
                </div>
            </div>
        </div>

        <div class="seo-columns-grid mt-24">
            <article class="minimal-card">
                <h3 class="card-title">Play While Chatting or on CupTV</h3>
                <p class="card-desc">Copy any card prompt straight into 1-to-1 chat or read it aloud during a 5-minute CupTV live video introduction.</p>
                <a href="{{ route('live-video.index') }}" class="text-link mt-8">Try on CupTV Live Video</a>
            </article>
            <article class="minimal-card">
                <h3 class="card-title">Bring It to Your Travel Group</h3>
                <p class="card-desc">Great for evening café tables in Shimla, Kasol, Manali, or Goa when getting to know fellow travelers.</p>
                <a href="{{ route('travel.groups') }}" class="text-link mt-8">Explore Travel Groups</a>
            </article>
            <article class="minimal-card">
                <h3 class="card-title">Respectful &amp; Safe Boundaries</h3>
                <p class="card-desc">Either person can always skip a card or switch modes. CupDate never displays intrusive ads or explicit dares.</p>
                <a href="{{ route('seo.icebreakers') }}" class="text-link mt-8">Read Icebreaker Guide</a>
            </article>
        </div>
    </div>
</section>
@endsection
