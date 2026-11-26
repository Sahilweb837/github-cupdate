@extends('layouts.app')

@section('title', 'Create Free Account | CupDate')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="auth-section section-cream">
    <div class="container auth-grid">
        <div>
            <span class="eyebrow-pill"><x-icon name="heart" class="cup-icon-xs" /> Free &amp; Verified Community</span>
            <h1 class="hero-title">Meet someone genuine over coffee.</h1>
            <p class="hero-subtitle">Your profile is saved securely in MySQL. Free chat, free video calling, and real local discovery across Himachal Pradesh.</p>
        </div>

        <div class="auth-card">
            <h2 class="card-title">Create Free Account</h2>
            @if ($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="cupdate-form mt-16">
                @csrf
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="dob">Date of Birth (18+)</label>
                        <input id="dob" name="dob" type="date" value="{{ old('dob', '1999-06-15') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="city">City (Himachal Pradesh)</label>
                        <select id="city" name="city" required>
                            @foreach ($cities as $city)
                                <option value="{{ $city->name }}" @selected(old('city') === $city->name)>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="gender">I am</label>
                        <select id="gender" name="gender" required>
                            <option value="female">Woman</option>
                            <option value="male">Man</option>
                            <option value="nonbinary">Non-binary</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preference">Interested in</label>
                        <select id="preference" name="preference" required>
                            <option value="everyone">Everyone</option>
                            <option value="female">Women</option>
                            <option value="male">Men</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="coffee_preference">Favorite Coffee</label>
                    <input id="coffee_preference" name="coffee_preference" type="text" value="{{ old('coffee_preference', 'Cappuccino') }}">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-pink btn-full">Create My CupDate Account</button>
            </form>

            <p class="auth-switch">Already have an account? <a href="{{ route('login') }}" class="text-link">Login</a></p>
        </div>
    </div>
</section>
@endsection
