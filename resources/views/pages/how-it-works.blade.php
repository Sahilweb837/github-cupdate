@extends('layouts.app')

@section('title', 'How CupDate Works — Meet Someone. Share a Coffee.')
@section('description', 'Learn how CupDate turns genuine profiles, free chat, and video calls into comfortable daytime coffee dates in Himachal Pradesh.')

@section('content')
<section class="page-hero section-cream">
    <div class="container centered narrow">
        <span class="eyebrow-text">HOW IT WORKS</span>
        <h1 class="page-title">From simple hello to a real coffee date.</h1>
        <p class="page-subtitle">No endless random swiping. CupDate keeps connection thoughtful, safe, and centered around real conversation.</p>
    </div>
</section>

<section class="section-white">
    <div class="container stack-lg">
        <article class="step-detail-card">
            <div>
                <div class="step-index">01</div>
                <h2 class="step-detail-title">Create a genuine profile</h2>
                <p class="step-detail-desc">Add your natural photo, select your Himachal city, share your coffee preference, and list real hobbies and interests.</p>
            </div>
            <div class="step-widget-panel">
                <div class="checklist-row"><span><x-icon name="user" class="cup-icon-xs" /> Clear daylight photo</span><span class="check-mark">✓</span></div>
                <div class="checklist-row"><span><x-icon name="map-pin" class="cup-icon-xs" /> Himachal city &amp; interests</span><span class="check-mark">✓</span></div>
                <div class="checklist-row"><span><x-icon name="shield" class="cup-icon-xs" /> Profile verification request</span><span class="pending-pill">Trusted Badge</span></div>
            </div>
        </article>

        <article class="step-detail-card">
            <div class="step-widget-panel">
                <div class="checklist-row"><span><x-icon name="compass" class="cup-icon-xs" /> Filter by Dharamshala, Kangra, Mandi, Shimla</span></div>
                <div class="checklist-row"><span><x-icon name="sparkles" class="cup-icon-xs" /> Compatibility score from shared interests</span></div>
            </div>
            <div>
                <div class="step-index">02</div>
                <h2 class="step-detail-title">Discover people &amp; send a request</h2>
                <p class="step-detail-desc">Browse verified profiles without pressure. Send a friend or connection request with a thoughtful note.</p>
            </div>
        </article>

        <article class="step-detail-card">
            <div>
                <div class="step-index">03</div>
                <h2 class="step-detail-title">Free chat &amp; WebRTC video call</h2>
                <p class="step-detail-desc">Talk comfortably via real-time text chat and optional one-to-one video calls before deciding to meet in person.</p>
            </div>
            <div class="step-widget-panel">
                <div class="chat-bubble-in">Would you like to grab a cappuccino this weekend?</div>
                <div class="chat-bubble-out">Saturday afternoon in McLeod Ganj sounds lovely!</div>
            </div>
        </article>

        <article class="step-detail-card">
            <div class="step-widget-panel">
                @foreach ($datePlaces->take(2) as $place)
                    <div class="cafe-spot-card">
                        <div class="cafe-spot-icon"><x-icon name="coffee" class="cup-icon-sm" /></div>
                        <div>
                            <div class="cafe-spot-name">{{ $place->name }}</div>
                            <div class="cafe-spot-address">{{ $place->address }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>
                <div class="step-index">04</div>
                <h2 class="step-detail-title">Plan your coffee date</h2>
                <p class="step-detail-desc">Pick a city, suggest a comfortable public café and preferred time, and keep your exact personal location private.</p>
            </div>
        </article>
    </div>
</section>

<section class="section-blush">
    <div class="container centered narrow">
        <h2 class="section-title">Ready to meet someone genuine?</h2>
        <p class="section-lead">Join CupDate free and explore verified people near you.</p>
        <a href="{{ route('register') }}" class="btn btn-pink">Create Free Account</a>
    </div>
</section>
@endsection
