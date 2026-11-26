@extends('layouts.app')

@section('title', $title . ' | CupDate')

@section('content')
<section class="page-hero section-cream">
    <div class="container narrow">
        <span class="eyebrow-text">CUPDATE POLICIES</span>
        <h1 class="page-title left">{{ $title }}</h1>
        <div class="legal-card mt-16">
            @if ($page === 'privacy-policy')
                <p>CupDate (cupdate.in) respects your privacy. Your email, password hash, and private messages are stored securely. Your exact street coordinates are never disclosed to other members.</p>
                <p>You control whether your profile appears in discovery and can permanently remove your account anytime from Profile Settings. Visitor city analytics rely only on non-invasive request metadata and do not sell your personal information.</p>
            @elseif ($page === 'terms-and-conditions')
                <p>Members must be at least 18 years old and provide accurate, genuine profile details. Adult content, impersonation, harassment, fake reviews, spam, and automated scraping are strictly prohibited.</p>
                <p>CupDate provides free access to chat, CupTV live video, and community pages subject to ongoing safety moderation and these community terms.</p>
            @elseif ($page === 'community-guidelines')
                <p>Be respectful, honest, and kind. CupDate exists for genuine friendships, relationships, and daytime coffee meetups. Any abusive messages or fake profiles may be reported and suspended.</p>
            @else
                <p>Always choose well-lit public cafés for first meetings, inform a trusted friend, and use CupDate’s built-in video call and report/block features whenever needed.</p>
            @endif
        </div>
    </div>
</section>
@endsection
