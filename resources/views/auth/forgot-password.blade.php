@extends('layouts.app')

@section('title', 'Forgot Password | CupDate')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="auth-section section-cream">
    <div class="container narrow">
        <div class="auth-card">
            <h1 class="card-title">Reset your password</h1>
            <p class="card-desc">Enter your account email and we will generate a secure password reset link.</p>

            @if (session('success'))
                <div class="alert-success mt-16">{{ session('success') }}</div>
            @endif
            @if (session('reset_link'))
                <div class="alert-info mt-16">
                    <strong>Reset Link:</strong> <a href="{{ session('reset_link') }}" class="text-link">Click here to reset password</a>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="cupdate-form mt-16">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" required>
                </div>
                <button type="submit" class="btn btn-pink btn-full">Send Reset Link</button>
            </form>
        </div>
    </div>
</section>
@endsection
