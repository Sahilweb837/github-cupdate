@extends('layouts.dashboard')

@section('title', 'My Profile | CupDate')

@section('content')
<div class="profile-banner">
    <x-avatar :user="$me" :alt="$me->full_name" class="profile-banner-avatar" />
    <div>
        <h1 class="profile-banner-name">{{ $me->full_name }}</h1>
        <p class="profile-banner-city">
            {{ $me->city }}, {{ $me->state }} ·
            {{ $me->is_verified ? 'Verified Profile' : 'Verification: ' . ucfirst($me->verification_status) }}
            @if ($me->is_premium) · CupDate Plus @endif
        </p>
    </div>
</div>

<div class="profile-grid">
    <div class="profile-card">
        <h2 class="card-title">Edit Your CupDate Profile</h2>
        <form method="POST" action="{{ route('app.profile.update') }}" enctype="multipart/form-data" class="cupdate-form mt-16">
            @csrf
            <div class="form-group">
                <label for="avatar">Profile Picture</label>
                <input id="avatar" name="avatar" type="file" accept="image/*">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $me->full_name) }}" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <select id="city" name="city" required>
                        @foreach ($cities as $city)
                            <option value="{{ $city->name }}" @selected($me->city === $city->name)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" name="dob" type="date" value="{{ old('dob', $me->dob?->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label for="coffee_preference">Coffee Order</label>
                    <input id="coffee_preference" name="coffee_preference" type="text" value="{{ old('coffee_preference', $me->coffee_preference) }}">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        @foreach (['female' => 'Woman', 'male' => 'Man', 'nonbinary' => 'Non-binary', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected($me->gender === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="preference">Relationship Preference</label>
                    <select id="preference" name="preference">
                        @foreach (['everyone' => 'Everyone', 'female' => 'Women', 'male' => 'Men'] as $val => $label)
                            <option value="{{ $val }}" @selected($me->preference === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="bio">About Me</label>
                <textarea id="bio" name="bio" rows="3">{{ old('bio', $me->bio) }}</textarea>
            </div>

            <div class="form-group">
                <label>Interests</label>
                <div class="interests-check-grid">
                    @php $mine = $me->interestsList(); @endphp
                    @foreach ($interestOptions as $opt)
                        <label class="checkbox-chip">
                            <input type="checkbox" name="interests[]" value="{{ $opt->name }}" @checked(in_array($opt->name, $mine, true))>
                            <span>{{ $opt->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="hobbies">Hobbies (comma separated)</label>
                <input id="hobbies" name="hobbies" type="text" value="{{ old('hobbies', implode(', ', $me->hobbiesList())) }}" placeholder="Reading, Morning hikes, Baking">
            </div>

            <label class="toggle-row">
                <span>Show my profile in Discover (Privacy Visibility)</span>
                <input type="checkbox" name="profile_visible" value="1" @checked($me->profile_visible)>
            </label>

            <button type="submit" class="btn btn-pink">Save Profile Updates</button>
        </form>
    </div>

    <div class="stack-sm">
        <div class="profile-card">
            <h2 class="card-title">Verification &amp; Security</h2>
            <p class="muted-small mt-8">Email status: {{ $me->email_verified_at ? 'Verified' : 'Not verified yet' }}</p>
            @if (! $me->email_verified_at)
                <form method="POST" action="{{ route('verification.send') }}" class="mt-12">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm btn-full">Verify My Email Now</button>
                </form>
            @endif

            @if (! $me->is_verified)
                <form method="POST" action="{{ route('app.profile.verify') }}" class="mt-12">
                    @csrf
                    <button type="submit" class="btn btn-blush btn-sm btn-full">
                        {{ $me->verification_status === 'pending' ? 'Verification Pending Review' : 'Request Profile Verification' }}
                    </button>
                </form>
            @endif
        </div>

        <div class="profile-card section-cream">
            <h2 class="card-title">Optional CupDate Plus</h2>
            <p class="muted-small mt-8">Messaging and video calling remain free for everyone. Optional Plus adds visibility boost and advanced discovery insights.</p>
            <form method="POST" action="{{ route('app.profile.premium') }}" class="mt-12">
                @csrf
                <button type="submit" class="btn btn-coffee btn-sm btn-full">
                    {{ $me->is_premium ? 'Active: CupDate Plus (Switch to Free)' : 'Preview CupDate Plus' }}
                </button>
            </form>
        </div>

        <div class="profile-card">
            <h2 class="card-title">Account Control</h2>
            <p class="muted-small mt-8">Permanently hide and delete your account from CupDate discovery.</p>
            <form method="POST" action="{{ route('app.profile.delete') }}" class="mt-12" onsubmit="return confirm('Are you sure you want to delete your CupDate account?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm btn-full">Delete My Account</button>
            </form>
        </div>
    </div>
</div>
@endsection
