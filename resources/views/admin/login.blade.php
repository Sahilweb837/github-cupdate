@extends('layouts.app')

@section('title', 'Admin Sign In | CupDate')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="auth-section section-cream">
    <div class="container narrow">
        <div class="auth-card">
            <span class="eyebrow-pill"><x-icon name="shield" class="cup-icon-xs" /> Secure Admin Portal</span>
            <h1 class="card-title mt-8">CupDate Admin Console</h1>
            <p class="muted-small">Role-based access for Super Admin, Moderator, Support Admin, and Content Manager.</p>

            @if ($errors->any())
                <div class="alert-error mt-16">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="cupdate-form mt-16">
                @csrf
                <div class="form-group">
                    <label for="email">Admin Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="admin@cupdate.in" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <button type="submit" class="btn btn-coffee btn-full">Sign In to Admin Dashboard</button>
            </form>
        </div>
    </div>
</section>
@endsection
