@extends('layouts.dashboard')

@section('title', 'Coffee Dates | CupDate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">REAL-WORLD MEETUPS</span>
        <h1 class="app-page-title">Plan Your Coffee Date</h1>
    </div>
</div>

<div class="dates-grid">
    <div class="dates-list-col">
        @forelse ($dates as $date)
            @php
                $isRequester = $date->requester_id === $me->id;
                $other = $isRequester ? $date->partner : $date->requester;
            @endphp
            <article class="date-card">
                <x-avatar :user="$other" :alt="$other?->firstName() ?? 'Member'" class="date-avatar" />
                <div class="date-info">
                    <div class="date-with">{{ $other?->firstName() ?? 'Member' }} · {{ $date->city }}</div>
                    <div class="date-venue">{{ $date->venue_suggestion }}</div>
                    <div class="date-when">{{ $date->preferred_date }} · {{ $date->meeting_preferences }}</div>
                </div>
                <div class="date-actions-col">
                    <span class="date-status status-{{ $date->status }}">{{ ucfirst($date->status) }}</span>
                    @if ($date->status === 'pending' && ! $isRequester)
                        <form method="POST" action="{{ route('app.coffee-dates.respond', $date) }}" class="inline-form">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn-pink btn-xs">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('app.coffee-dates.respond', $date) }}" class="inline-form">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-outline btn-xs">Decline</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="empty-state-card">
                <h3>No coffee dates scheduled yet</h3>
                <p>Send a relaxed daytime coffee invitation to someone you’ve connected with.</p>
            </div>
        @endforelse
    </div>

    <div class="date-invite-card">
        <h2 class="card-title">Suggest a Coffee Date</h2>
        <p class="muted-small mb-12">Your exact location is never shared-only the public city &amp; café you choose.</p>

        <form method="POST" action="{{ route('app.coffee-dates.store') }}" class="cupdate-form">
            @csrf
            <div class="form-group">
                <label for="partner_id">Connection</label>
                <select id="partner_id" name="partner_id" required>
                    @foreach ($candidates as $candidate)
                        <option value="{{ $candidate->id }}" @selected((int) request('partner') === $candidate->id)>
                            {{ $candidate->firstName() }} ({{ $candidate->city }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <select id="city" name="city" required>
                    @foreach ($cities as $city)
                        <option value="{{ $city->name }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="venue_suggestion">Café Suggestion</label>
                <input id="venue_suggestion" name="venue_suggestion" type="text" list="himachalCafes" placeholder="e.g. Illiterati Books & Cafe" required>
                <datalist id="himachalCafes">
                    @foreach ($datePlaces as $place)
                        <option value="{{ $place->name }} ({{ $place->address }})">
                    @endforeach
                </datalist>
            </div>
            <div class="form-group">
                <label for="preferred_date">Preferred Date &amp; Time</label>
                <input id="preferred_date" name="preferred_date" type="text" placeholder="Saturday, 4:00 PM" required>
            </div>
            <div class="form-group">
                <label for="meeting_preferences">Meeting Preferences</label>
                <input id="meeting_preferences" name="meeting_preferences" type="text" placeholder="Daytime cappuccino & quiet chat">
            </div>
            <button type="submit" class="btn btn-pink btn-full">Send Coffee Invitation</button>
        </form>
    </div>
</div>
@endsection
