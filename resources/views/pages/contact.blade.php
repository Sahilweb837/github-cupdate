@extends('layouts.app')

@section('title', 'Contact — Cupdate')

@section('content')
<section class="page-hero">
    <div class="container contact-layout">
        <div class="contact-intro">
            <span class="eyebrow-text">CONTACT</span>
            <h1 class="page-title left">Say hello,<br>We’re listening.</h1>
            <p class="page-subtitle left">
                Questions about your account, partnership ideas, or safety support? Our Portland team replies within one business day.
            </p>

            <div class="contact-cards">
                <div class="contact-mini-card">
                    <div class="contact-mini-title">Email us</div>
                    <div class="contact-mini-sub">hello@cupdate.app</div>
                </div>
                <div class="contact-mini-card">
                    <div class="contact-mini-title">Press &amp; media</div>
                    <div class="contact-mini-sub">press@cupdate.app</div>
                </div>
                <div class="contact-mini-card">
                    <div class="contact-mini-title">Safety desk</div>
                    <div class="contact-mini-sub">24/7 priority moderation response</div>
                </div>
            </div>
        </div>

        <div class="contact-form-card">
            @if (session('contact_success'))
                <div class="alert-success" role="status">
                    {{ session('contact_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.submit') }}" class="cupdate-form">
                @csrf
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Sofia Alvarez" required>
                        @error('name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="sofia@example.com" required>
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="topic">Topic</label>
                    <select id="topic" name="topic">
                        <option value="General question">General question</option>
                        <option value="Live Radar feedback">Live Radar feedback</option>
                        <option value="Safety & verification">Safety &amp; verification</option>
                        <option value="Café partnership">Café partnership</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Tell us what's on your mind..." required>{{ old('message') }}</textarea>
                    @error('message')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-coral btn-full">Send message →</button>
            </form>
        </div>
    </div>
</section>
@endsection
