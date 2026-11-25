@extends('layouts.app')

@section('title', 'About CupDate — Meet Someone. Share a Coffee.')
@section('description', 'CupDate (cupdate.in) connects thoughtful singles and friends across Himachal Pradesh through safe chat, video calling, and coffee dates.')

@section('content')
<section class="page-hero section-cream">
    <div class="container centered narrow">
        <span class="eyebrow-text">ABOUT US</span>
        <h1 class="page-title">Meet Someone. Share a Coffee. Create a Connection.</h1>
        <p class="page-subtitle">CupDate was created for people tired of noisy, impersonal swiping apps. We focus on real profiles, respectful conversation, and meaningful coffee meetups in Himachal Pradesh.</p>
    </div>
</section>

<section class="section-white">
    <div class="container editorial-grid">
        <div>
            <img src="{{ asset('images/hero-coffee-couple.jpg') }}" alt="Couple sharing coffee in a sunlit cafe" loading="lazy" class="editorial-photo">
        </div>
        <div>
            <span class="eyebrow-text">OUR PHILOSOPHY</span>
            <h2 class="section-title">Real connections take warmth and trust</h2>
            <p class="section-lead">Whether you are in Shimla, Dharamshala, Kangra, Mandi, Palampur, or Manali, CupDate gives you a calm, dignified space to meet people looking for genuine relationships, friendship, and conversation.</p>
            <div class="tag-list mt-16">
                @foreach ($cities as $city)
                    <span class="tag-soft">{{ $city->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
