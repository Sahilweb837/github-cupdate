@extends('layouts.app')

@section('title', 'How it works — Cupdate')

@section('content')
<section class="page-hero">
    <div class="container centered narrow">
        <span class="eyebrow-text">HOW IT WORKS</span>
        <h1 class="page-title">From hello to first date in four steps.</h1>
        <p class="page-subtitle">No endless swiping. Cupdate keeps things simple and moving.</p>
    </div>
</section>

<section class="section-compact">
    <div class="container stack-lg">
        <article class="step-detail-card">
            <div class="step-detail-copy">
                <div class="step-index">01</div>
                <h2 class="step-detail-title">Build your profile</h2>
                <p class="step-detail-desc">Add photos, answer fun prompts and pick your interests. Verify with a quick selfie to earn the badge.</p>
            </div>
            <div class="step-widget-panel">
                <div class="checklist-row is-done">
                    <span>📷 Add 3 photos</span>
                    <span class="check-mark">✓</span>
                </div>
                <div class="checklist-row is-done">
                    <span>✎ Answer 2 prompts</span>
                    <span class="check-mark">✓</span>
                </div>
                <div class="checklist-row is-pending">
                    <span>🛡 Verify yourself</span>
                    <span class="pending-pill">Pending</span>
                </div>
            </div>
        </article>

        <article class="step-detail-card reverse-on-desktop">
            <div class="step-widget-panel compat-panel">
                <div class="compat-ring">
                    <span class="compat-percent">94%</span>
                    <span class="compat-label">Compatible</span>
                </div>
                <ul class="compat-traits">
                    <li>❤ Both love hiking</li>
                    <li>❤ Same life goals</li>
                    <li>❤ Night owls</li>
                </ul>
            </div>
            <div class="step-detail-copy">
                <div class="step-index">02</div>
                <h2 class="step-detail-title">Get smart matches</h2>
                <p class="step-detail-desc">Every morning, receive curated picks. Your compatibility score updates as you do.</p>
            </div>
        </article>

        <article class="step-detail-card">
            <div class="step-detail-copy">
                <div class="step-index">03</div>
                <h2 class="step-detail-title">Chat &amp; check the radar</h2>
                <p class="step-detail-desc">Message after a mutual like, or use Radar to see who’s nearby and open to meeting today.</p>
            </div>
            <div class="step-widget-panel chat-preview-panel">
                <div class="chat-bubble-in">Coffee this weekend? ☕</div>
                <div class="chat-bubble-out">Saturday at 4 works!</div>
            </div>
        </article>

        <article class="step-detail-card reverse-on-desktop">
            <div class="step-widget-panel venue-panel">
                <div class="venue-card-mini">
                    <span class="venue-badge-icon" aria-hidden="true">☕</span>
                    <div>
                        <div class="venue-name">Blue Bottle Coffee</div>
                        <div class="venue-meta">Sat, 4:00 PM · Confirmed</div>
                    </div>
                </div>
            </div>
            <div class="step-detail-copy">
                <div class="step-index">04</div>
                <h2 class="step-detail-title">Book the date</h2>
                <p class="step-detail-desc">Pick a venue, send an invite and share a safety check-in. We’ll remind you both.</p>
            </div>
        </article>
    </div>
</section>

<section class="section-block">
    <div class="container narrow">
        <div class="section-heading centered">
            <h2 class="section-title">Questions, answered</h2>
        </div>

        <div class="faq-list" data-faq-accordion>
            @foreach ($faqs as $index => $faq)
                <div class="faq-item {{ $index === 0 ? 'is-open' : '' }}">
                    <button type="button" class="faq-question" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                        <span>{{ $faq['q'] }}</span>
                        <span class="faq-chevron" aria-hidden="true">⌄</span>
                    </button>
                    <div class="faq-answer">
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-block cta-section-wrap">
    <div class="container">
        <div class="coral-cta-banner">
            <h2 class="coral-cta-title">Start your first update</h2>
            <a href="{{ route('join') }}" class="btn btn-plum">Join Cupdate free</a>
        </div>
    </div>
</section>
@endsection
