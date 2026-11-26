@extends('layouts.app')

@section('title', 'Safety Center | Your Safety Comes First | CupDate')
@section('description', 'Learn how CupDate protects members with profile verification, secure authentication, report & block tools, privacy settings, and active moderation.')

@section('content')
<section class="page-hero section-cream">
    <div class="container centered narrow">
        <span class="eyebrow-text">SAFETY CENTER</span>
        <h1 class="page-title">Your Safety Comes First.</h1>
        <p class="page-subtitle">CupDate is built for genuine, respectful relationships and friendships-with zero tolerance for fake profiles, adult content, or harassment.</p>
    </div>
</section>

<section class="section-white">
    <div class="container cards-grid-3">
        <article class="minimal-card">
            <div class="card-icon-soft"><x-icon name="shield" class="cup-icon-md" /></div>
            <h2 class="card-title">Profile Verification</h2>
            <p class="card-desc">Members can verify their email and submit profile verification requests reviewed by our moderation team.</p>
        </article>
        <article class="minimal-card">
            <div class="card-icon-soft"><x-icon name="lock" class="cup-icon-md" /></div>
            <h2 class="card-title">Privacy &amp; Location Safety</h2>
            <p class="card-desc">Only your selected city is displayed-never your street address or live coordinates without explicit personal consent.</p>
        </article>
        <article class="minimal-card">
            <div class="card-icon-soft"><x-icon name="user" class="cup-icon-md" /></div>
            <h2 class="card-title">Block &amp; Report Anytime</h2>
            <p class="card-desc">One-tap block immediately prevents all messages and visibility, while reports go straight to our moderators.</p>
        </article>
        <article class="minimal-card">
            <div class="card-icon-soft"><x-icon name="video" class="cup-icon-md" /></div>
            <h2 class="card-title">Pre-Date Video Calls</h2>
            <p class="card-desc">Use CupDate’s built-in authenticated WebRTC video calls to get comfortable before meeting at a public café.</p>
        </article>
        <article class="minimal-card">
            <div class="card-icon-soft"><x-icon name="coffee" class="cup-icon-md" /></div>
            <h2 class="card-title">Daytime Public Meetups</h2>
            <p class="card-desc">We encourage first meetings at busy, well-lit daytime coffee shops and letting a trusted friend know your plans.</p>
        </article>
        <article class="minimal-card">
            <div class="card-icon-soft"><x-icon name="sparkles" class="cup-icon-md" /></div>
            <h2 class="card-title">Strict Community Guidelines</h2>
            <p class="card-desc">No spam, no adult content, and no impersonation. Suspended accounts are locked out immediately.</p>
        </article>
    </div>
</section>
@endsection
