@extends('layouts.dashboard')

@section('title', 'Live Radar | Cupdate')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">PROXIMITY DISCOVERY</span>
        <h1 class="app-page-title">Live Radar</h1>
    </div>
    <button type="button" class="btn btn-outline btn-sm" data-radar-toggle>Radar: Active (800m)</button>
</div>

<div class="radar-layout">
    <div class="radar-stage">
        <div class="radar-ring ring-outer"></div>
        <div class="radar-ring ring-mid"></div>
        <div class="radar-ring ring-inner"></div>
        <div class="radar-sweep" aria-hidden="true"></div>

        <div class="radar-center-dot" title="You ({{ $user['name'] }})">You</div>

        @foreach ($nearby as $idx => $person)
            <button type="button"
                class="radar-pin {{ $idx === 0 ? 'is-selected' : '' }}"
                style="left: {{ $person['x'] }}%; top: {{ $person['y'] }}%;"
                data-radar-pin="{{ $idx }}"
                title="{{ $person['name'] }} at {{ $person['spot'] }}">
                <img src="{{ asset($person['avatar']) }}" alt="{{ $person['name'] }}">
                <span>{{ $person['match'] }}%</span>
            </button>
        @endforeach
    </div>

    <div class="radar-sidebar-list">
        <h2 class="panel-heading">People nearby right now</h2>
        @foreach ($nearby as $idx => $person)
            <div class="nearby-row {{ $idx === 0 ? 'is-selected' : '' }}" data-nearby-row="{{ $idx }}">
                <img src="{{ asset($person['avatar']) }}" alt="{{ $person['name'] }}" class="nearby-avatar">
                <div class="nearby-info">
                    <div class="nearby-name">{{ $person['name'] }} · <span>{{ $person['match'] }}%</span></div>
                    <div class="nearby-spot">{{ $person['spot'] }} · {{ $person['distance'] }}</div>
                </div>
                <button type="button" class="btn btn-coral btn-xs" data-wave-btn>Wave</button>
            </div>
        @endforeach
    </div>
</div>
@endsection
