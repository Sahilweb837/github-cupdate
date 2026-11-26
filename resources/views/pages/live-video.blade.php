@extends('layouts.app')

@section('title', 'CupTV | World’s Best Free Live Video Chat & Coffee Dating | CupDate')
@section('description', 'Join CupTV on CupDate - the world’s best 100% free, safe, moderated 1-to-1 live video chat across 100+ Indian cities & worldwide with zero paid paywalls.')

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebApplication",
    "name": "CupTV Free Live Video Chat",
    "url": "{{ route('live-video.index') }}",
    "applicationCategory": "SocialNetworkingApplication",
    "operatingSystem": "Web, Android, iOS",
    "offers": {
        "@@type": "Offer",
        "price": "0",
        "priceCurrency": "INR"
    },
    "description": "World’s best 100% free live video chat and coffee dating cam across 100+ Indian cities and worldwide with anti-nudity and anti-spam safety."
}
</script>
@endpush

@section('content')
<section class="page-hero section-cream">
    <div class="container">
        <div class="live-hero-head">
            <div>
                <span class="eyebrow-pill"><span class="online-dot"></span> CUPTV LIVE VIDEO · 100% FREE WORLDWIDE</span>
                <h1 class="page-title left mt-12">CupTV: World’s Best Free Live Video Chat</h1>
                <p class="page-subtitle left">Instant 1-to-1 live video matching with verified members &amp; safe guests across 100+ Indian cities and worldwide. Zero paid paywalls, strict zero-nudity &amp; anti-spam rules.</p>
            </div>
            <div class="live-stats-card">
                <div class="live-stat-row">
                    <span>Active Visitors</span>
                    <strong data-live-visitors>{{ $stats['active_visitors'] }}</strong>
                </div>
                <div class="live-stat-row">
                    <span>Members Online</span>
                    <strong data-live-members>{{ $stats['online_members'] }}</strong>
                </div>
                <div class="live-stat-row">
                    <span>In Video Queue</span>
                    <strong id="liveQueueCount">{{ $stats['live_video_active'] }}</strong>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-white">
    <div class="container">
        <div class="ometv-shell"
             id="ometvApp"
             data-join-url="{{ route('live-video.join') }}"
             data-poll-url="{{ route('live-video.poll') }}"
             data-signal-url="{{ route('live-video.signal') }}"
             data-chat-url="{{ route('live-video.chat') }}"
             data-leave-url="{{ route('live-video.leave') }}"
             data-report-url="{{ route('live-video.report') }}">

            <div class="ometv-stage-col">
                <div class="ometv-videos-grid">
                    <div class="ometv-video-box">
                        <video id="omeRemoteVideo" autoplay playsinline></video>
                        <div id="omeRemoteOverlay" class="ometv-overlay">
                            <x-icon name="video" class="cup-icon-lg" />
                            <strong id="omePartnerLabel">Click "Start Live Match" to connect</strong>
                            <p id="omeStatusSub">100% Free · Moderated &amp; Encrypted WebRTC</p>
                        </div>
                        <span class="ometv-badge" id="omePartnerBadge" hidden>Connected</span>
                    </div>

                    <div class="ometv-video-box is-self">
                        <video id="omeLocalVideo" autoplay muted playsinline></video>
                        <div id="omeLocalOverlay" class="ometv-overlay">
                            <x-icon name="user" class="cup-icon-md" />
                            <span>Your Camera Preview</span>
                        </div>
                        <span class="ometv-badge">{{ $me ? $me->firstName() : 'You (Guest)' }}</span>
                    </div>
                </div>

                <div class="ometv-controls">
                    <select id="omeTargetGender" aria-label="Who to match with">
                        <option value="everyone">Match: Everyone</option>
                        <option value="female">Match: Women</option>
                        <option value="male">Match: Men</option>
                    </select>
                    <select id="omeCountry" aria-label="Region">
                        <option value="all">Region: All World</option>
                        <option value="India">India</option>
                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                        <option value="United States">United States</option>
                        <option value="United Kingdom">United Kingdom</option>
                    </select>
                    <button type="button" id="omeStartBtn" class="btn btn-pink btn-sm">Start Live Match</button>
                    <button type="button" id="omeNextBtn" class="btn btn-coffee btn-sm" disabled>Next Match →</button>
                    <button type="button" id="omeMuteBtn" class="btn btn-outline btn-sm" disabled>Mute</button>
                    <button type="button" id="omeCamBtn" class="btn btn-outline btn-sm" disabled>Cam Off</button>
                    <button type="button" id="omeStopBtn" class="btn btn-outline btn-sm" disabled>Stop</button>
                    <button type="button" id="omeReportBtn" class="btn btn-outline btn-sm" disabled>Report / Safety</button>
                </div>

                <div class="safety-strip mt-12">
                    <x-icon name="shield" class="cup-icon-xs" />
                    <span>Zero-nudity &amp; anti-spam rule strictly enforced. Inappropriate behavior triggers instant disconnect and report logging.</span>
                </div>
            </div>

            <aside class="ometv-chat-col">
                <div class="ometv-chat-head">
                    <strong>Live Room Chat</strong>
                    <span class="muted-small">Filtered for spam &amp; safety</span>
                </div>
                <div id="omeChatFeed" class="ometv-chat-feed">
                    <div class="muted-small">Connect with a match to chat live alongside video.</div>
                </div>
                <form id="omeChatForm" class="ometv-chat-form">
                    <input type="text" id="omeChatInput" placeholder="Say hello respectfully..." maxlength="300" disabled>
                    <button type="submit" id="omeChatSend" class="btn btn-pink btn-sm" disabled>Send</button>
                </form>
                @guest
                    <div class="ometv-join-box mt-12">
                        <strong>Want to save matches &amp; schedule coffee dates?</strong>
                        <p class="muted-small">Join CupDate free forever in 20 seconds.</p>
                        <a href="{{ route('register') }}" class="btn btn-pink btn-xs btn-full mt-8">Join Now - 100% Free</a>
                    </div>
                @endguest
            </aside>
        </div>
    </div>
</section>
@endsection
