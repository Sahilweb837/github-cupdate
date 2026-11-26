@extends('layouts.dashboard')

@section('title', 'Dates | Cupdate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">YOUR SCHEDULE</span>
        <h1 class="app-page-title">Coffee Dates</h1>
    </div>
</div>

<div class="dates-grid">
    <div class="dates-list-col" data-dates-list>
        @foreach ($upcoming as $date)
            <article class="date-card">
                <img src="{{ asset($date['avatar']) }}" alt="{{ $date['with'] }}" class="date-avatar">
                <div class="date-info">
                    <div class="date-with">{{ $date['with'] }}</div>
                    <div class="date-venue">{{ $date['venue'] }}</div>
                    <div class="date-when">{{ $date['when'] }}</div>
                </div>
                <span class="date-status {{ $date['status'] === 'Confirmed' ? 'is-confirmed' : '' }}">
                    {{ $date['status'] }}
                </span>
            </article>
        @endforeach
    </div>

    <div class="date-invite-card">
        <h2 class="panel-heading">Book a new coffee date</h2>
        <form class="cupdate-form" data-book-date-form>
            <div class="form-group">
                <label for="dateWith">Match</label>
                <select id="dateWith" name="with">
                    <option value="Yuki, 28">Yuki, 28 (89% match)</option>
                    <option value="Diego, 29">Diego, 29 (94% match)</option>
                    <option value="Ben, 31">Ben, 31 (91% match)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dateVenue">Café spot</label>
                <input id="dateVenue" name="venue" type="text" value="Heart Coffee Roasters · Burnside" required>
            </div>
            <div class="form-group">
                <label for="dateWhen">Day &amp; time</label>
                <input id="dateWhen" name="when" type="text" value="Sun, Nov 29 · 11:00 AM" required>
            </div>
            <button type="submit" class="btn btn-coral btn-full">Send coffee invite</button>
        </form>
    </div>
</div>
@endsection
