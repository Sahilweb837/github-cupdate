@extends('layouts.admin')

@section('title', 'SEO, Ads & Platform Settings | CupDate Admin')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">CONFIGURATION</span>
        <h1 class="app-page-title">Homepage, SEO, Ads &amp; Platform Settings</h1>
    </div>
</div>

<section class="admin-panel-card">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="cupdate-form">
        @csrf
        <h2 class="card-title">Homepage Copy</h2>
        <div class="form-group">
            <label for="homepage_headline">Hero Headline</label>
            <input id="homepage_headline" name="homepage_headline" type="text" value="{{ $settings['homepage_headline'] ?? 'Your Next Connection Starts With a Coffee.' }}">
        </div>
        <div class="form-group">
            <label for="homepage_subheadline">Hero Subheadline</label>
            <textarea id="homepage_subheadline" name="homepage_subheadline" rows="2">{{ $settings['homepage_subheadline'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label for="coffee_section_heading">Coffee Date Section Heading</label>
            <input id="coffee_section_heading" name="coffee_section_heading" type="text" value="{{ $settings['coffee_section_heading'] ?? 'Less Swiping. More Meaningful Meetings.' }}">
        </div>

        <h2 class="card-title mt-16">SEO Defaults</h2>
        <div class="form-group">
            <label for="seo_default_title">Default Meta Title</label>
            <input id="seo_default_title" name="seo_default_title" type="text" value="{{ $settings['seo_default_title'] ?? '' }}">
        </div>
        <div class="form-group">
            <label for="seo_default_description">Default Meta Description</label>
            <textarea id="seo_default_description" name="seo_default_description" rows="2">{{ $settings['seo_default_description'] ?? '' }}</textarea>
        </div>

        <h2 class="card-title mt-16">Advertisement Placements &amp; Feature Toggles</h2>
        <div class="form-row-2">
            <div class="form-group">
                <label for="ads_enabled">Show Ad Slot Banner</label>
                <select id="ads_enabled" name="ads_enabled">
                    <option value="0" @selected(($settings['ads_enabled'] ?? '0') === '0')>Disabled</option>
                    <option value="1" @selected(($settings['ads_enabled'] ?? '0') === '1')>Enabled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="platform_video_calls">WebRTC Video Calling</label>
                <select id="platform_video_calls" name="platform_video_calls">
                    <option value="1" @selected(($settings['platform_video_calls'] ?? '1') === '1')>Enabled</option>
                    <option value="0" @selected(($settings['platform_video_calls'] ?? '1') === '0')>Maintenance</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="ads_slot_html">Advertisement Banner HTML</label>
            <textarea id="ads_slot_html" name="ads_slot_html" rows="2" placeholder="Optional clean café partner banner HTML">{{ $settings['ads_slot_html'] ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-pink">Save All Platform Settings</button>
    </form>
</section>
@endsection
