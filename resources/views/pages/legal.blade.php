@extends('layouts.app')

@section('title', $title . ' — Cupdate')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="eyebrow-text">CUPDATE TRUST</span>
        <h1 class="page-title left">{{ $title }}</h1>
        <div class="legal-card">
            @if ($section === 'privacy')
                <p>Cupdate collects only what is needed to power live compatibility updates, optional café-radius Radar visibility, and verified member profiles.</p>
                <p>Your exact street coordinates are never exposed to other members. You can pause Radar or delete your account anytime from your Profile settings.</p>
            @elseif ($section === 'terms')
                <p>Cupdate is built for respectful, in-person connection. Members agree to use authentic photos, complete selfie verification honestly, and treat others with kindness.</p>
                <p>Automated scraping, spam, or harassment results in immediate removal by our moderation team.</p>
            @else
                <p>Every Cupdate profile supports selfie verification, one-tap reporting, and optional date check-in reminders so a trusted friend knows when and where you’re meeting.</p>
                <p>Need urgent help? Reach our 24/7 safety desk via the Contact page.</p>
            @endif
            <div class="legal-actions">
                <a href="{{ route('home') }}" class="btn btn-outline">← Back to Home</a>
                <a href="{{ route('contact') }}" class="btn btn-coral">Contact safety team</a>
            </div>
        </div>
    </div>
</section>
@endsection
