@extends('layouts.app')

@section('title', 'Contact CupDate — Support & Feedback')
@section('description', 'Reach the CupDate team for account help, safety reports, café partnerships, or general feedback.')

@section('content')
<section class="page-hero section-cream">
    <div class="container contact-layout">
        <div>
            <span class="eyebrow-text">CONTACT SUPPORT</span>
            <h1 class="page-title left">We’re here to help.</h1>
            <p class="page-subtitle left">Have a question about profile verification, safety, or partnerships in Himachal Pradesh? Send us a note and our support team will respond via the CupDate Admin Desk.</p>

            <div class="contact-cards">
                <div class="contact-mini-card">
                    <strong>Support Email</strong>
                    <span>support@cupdate.in</span>
                </div>
                <div class="contact-mini-card">
                    <strong>Safety &amp; Moderation</strong>
                    <span>Priority review for all member safety inquiries</span>
                </div>
            </div>
        </div>

        <div class="contact-form-card">
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('contact.submit') }}" class="cupdate-form">
                @csrf
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()?->full_name) }}" required>
                        @error('name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" required>
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="general">General Question</option>
                            <option value="safety">Safety &amp; Verification</option>
                            <option value="account">Account Support</option>
                            <option value="partnership">Café Partnership</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required>
                        @error('subject')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                    @error('message')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-pink btn-full">Send Support Message</button>
            </form>
        </div>
    </div>
</section>
@endsection
