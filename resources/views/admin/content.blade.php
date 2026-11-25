@extends('layouts.admin')

@section('title', 'Content & Taxonomy — CupDate Admin')

@section('content')
<div class="app-header-row">
    <div>
        <span class="eyebrow-text">CONTENT MANAGEMENT</span>
        <h1 class="app-page-title">Cities, Interests &amp; Blog</h1>
    </div>
</div>

<div class="admin-two-col">
    <section class="admin-panel-card">
        <h2 class="card-title">City Management</h2>
        <form method="POST" action="{{ route('admin.cities.store') }}" class="cupdate-form mt-12">
            @csrf
            <div class="form-row-2">
                <input type="text" name="name" placeholder="City name (e.g. Kasol)" required>
                <input type="text" name="state" value="Himachal Pradesh" required>
            </div>
            <input type="text" name="description" placeholder="Short description">
            <button type="submit" class="btn btn-pink btn-sm">Add City</button>
        </form>
        <div class="tag-list mt-16">
            @foreach ($cities as $city)
                <span class="tag-soft">{{ $city->name }} ({{ $city->state }})</span>
            @endforeach
        </div>
    </section>

    <section class="admin-panel-card">
        <h2 class="card-title">Interest Categories</h2>
        <form method="POST" action="{{ route('admin.interests.store') }}" class="cupdate-form mt-12">
            @csrf
            <div class="form-row-2">
                <input type="text" name="name" placeholder="Interest name (e.g. Stargazing)" required>
                <button type="submit" class="btn btn-pink btn-sm">Add Interest</button>
            </div>
        </form>
        <div class="tag-list mt-16">
            @foreach ($interests as $interest)
                <span class="tag-soft">{{ $interest->name }}</span>
            @endforeach
        </div>
    </section>
</div>

<section class="admin-panel-card mt-24">
    <h2 class="card-title">Publish Blog Post</h2>
    <form method="POST" action="{{ route('admin.blog.store') }}" class="cupdate-form mt-12">
        @csrf
        <div class="form-row-2">
            <input type="text" name="title" placeholder="Article title" required>
            <input type="text" name="meta_title" placeholder="SEO meta title (optional)">
        </div>
        <input type="text" name="excerpt" placeholder="Short summary" required>
        <textarea name="body" rows="4" placeholder="Full article content..." required></textarea>
        <button type="submit" class="btn btn-coffee btn-sm">Publish Article</button>
    </form>

    <div class="stack-sm mt-20">
        @foreach ($posts as $post)
            <div class="moderation-item">
                <div>
                    <strong>{{ $post->title }}</strong>
                    <div class="muted-small">/blog/{{ $post->slug }}</div>
                </div>
                <a href="{{ route('blog.show', $post->slug) }}" class="text-link">View →</a>
            </div>
        @endforeach
    </div>
</section>
@endsection
