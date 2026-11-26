@extends('layouts.app')

@section('title', 'Set New Password | CupDate')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="auth-section section-cream">
    <div class="container narrow">
        <div class="auth-card">
            <h1 class="card-title">Choose a new password</h1>
            @if ($errors->any())
                <div class="alert-error mt-16">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('password.update') }}" class="cupdate-form mt-16">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
                <button type="submit" class="btn btn-pink btn-full">Update Password</button>
            </form>
        </div>
    </div>
</section>
@endsection
