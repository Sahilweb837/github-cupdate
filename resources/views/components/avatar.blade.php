@props(['user' => null, 'class' => '', 'alt' => 'Member'])
@if ($user && $user->hasRealAvatar())
    <img src="{{ $user->avatarUrl() }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $class]) }}>
@else
    <span {{ $attributes->merge(['class' => trim('avatar-monogram ' . $class)]) }} aria-label="{{ $alt }}">
        {{ $user ? $user->initials() : 'C' }}
    </span>
@endif
