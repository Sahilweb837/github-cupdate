@extends('layouts.app')

@section('title', $title . ' — CupDate')

@section('content')
<section class="page-hero section-cream">
    <div class="container narrow">
        <span class="eyebrow-text">CUPDATE POLICIES</span>
        <h1 class="page-title left">{{ $title }}</h1>
        <div class="legal-card mt-16">
            @if ($page === 'privacy-policy')
                <p>CupDate respects your privacy. Your email, password hash, and private messages are stored securely. Your exact street coordinates are never disclosed to other members.</p>
                <p>You control whether your profile appears in discovery and can permanently remove your account anytime from Profile Settings.</p>
            @elseif ($page === 'terms-and-conditions')
                <p>Members must be at least 18 years old and provide accurate, genuine profile details. Adult content, impersonation, harassment, and automated scraping are strictly prohibited.</p>
            @elseif ($page === 'community-guidelines')
                <p>Be respectful, honest, and kind. CupDate exists for genuine friendships, relationships, and daytime coffee meetups. Any abusive messages or fake profiles may be reported and suspended.</p>
            @else
                <p>Always choose well-lit public cafés for first meetings, inform a trusted friend, and use CupDate’s built-in video call and report/block features whenever needed.</p>
            @endif
        </div>
    </div>
</section>
@endsection
