@extends('layouts.dashboard')

@section('title', 'Video Calling | CupDate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">WEBRTC VIDEO CALL</span>
        <h1 class="app-page-title">One-to-One Video Call</h1>
    </div>
    <span class="app-status-pill" id="videoCallTimer">00:00</span>
</div>

<div class="video-layout"
     data-webrtc-app
     data-start-url="{{ route('app.video.start') }}"
     data-incoming-url="{{ route('app.video.incoming') }}"
     data-partner-id="{{ $partner?->id ?? '' }}">

    <div class="video-stage-card">
        <div class="video-streams-wrap">
            <video id="remoteVideo" class="remote-video-el" autoplay playsinline></video>
            <video id="localVideo" class="local-video-el" autoplay muted playsinline></video>
            <div id="videoPlaceholder" class="video-placeholder">
                <x-icon name="video" class="cup-icon-lg" />
                <p id="videoStatusText">
                    {{ $partner ? 'Ready to call ' . $partner->firstName() : 'Select a connection to start a secure WebRTC video call' }}
                </p>
            </div>
        </div>

        <div class="video-controls-bar">
            <select id="callPartnerSelect" aria-label="Select connection to call">
                <option value="">Choose a connection...</option>
                @foreach ($contacts as $contact)
                    <option value="{{ $contact->id }}" @selected($partner && $partner->id === $contact->id)>
                        {{ $contact->firstName() }} ({{ $contact->city }})
                    </option>
                @endforeach
            </select>
            <button type="button" id="startCallBtn" class="btn btn-pink btn-sm">Start Call</button>
            <button type="button" id="acceptCallBtn" class="btn btn-coffee btn-sm" hidden>Accept Incoming</button>
            <button type="button" id="toggleMuteBtn" class="btn btn-outline btn-sm" disabled>Mute Mic</button>
            <button type="button" id="toggleCamBtn" class="btn btn-outline btn-sm" disabled>Camera Off</button>
            <button type="button" id="endCallBtn" class="btn btn-outline btn-sm" disabled>End Call</button>
        </div>
    </div>

    <aside class="minimal-card">
        <h2 class="card-title">Recent Call History</h2>
        <div class="call-history-list mt-16">
            @forelse ($callHistory as $call)
                @php
                    $isCaller = $call->caller_id === $me->id;
                    $other = $isCaller ? $call->receiver : $call->caller;
                @endphp
                <div class="call-history-row">
                    <div>
                        <strong>{{ $other?->firstName() ?? 'Member' }}</strong>
                        <div class="muted-small">{{ $isCaller ? 'Outgoing' : 'Incoming' }} · {{ ucfirst($call->status) }}</div>
                    </div>
                    <span class="muted-small">
                        {{ $call->duration_seconds ? gmdate('i:s', $call->duration_seconds) : ($call->created_at?->diffForHumans() ?? '') }}
                    </span>
                </div>
            @empty
                <p class="muted-small">No calls yet. Start a quick video hello before meeting for coffee.</p>
            @endforelse
        </div>
    </aside>
</div>
@endsection
