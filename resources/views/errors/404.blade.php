<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Page Not Found (404) | CupDate.in</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/cupdate-logo.svg') }}">
    <style>
        body { margin: 0; font-family: system-ui, -apple-system, sans-serif; background: #FFF8F0; color: #382820; display: grid; place-items: center; min-height: 100vh; padding: 24px; }
        .card { max-width: 520px; width: 100%; background: #fff; border: 1px solid #ECE4E6; border-radius: 20px; padding: 36px 28px; text-align: center; box-shadow: 0 10px 28px rgba(56,40,32,.07); }
        .logo { width: 52px; height: 48px; margin: 0 auto 12px; display: block; }
        h1 { margin: 0 0 8px; font-size: 1.75rem; color: #382820; }
        p { margin: 0 0 22px; color: #77717A; line-height: 1.6; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
        a { text-decoration: none; padding: 10px 18px; border-radius: 999px; font-weight: 600; font-size: 0.9rem; }
        .primary { background: #D96B89; color: #fff; }
        .secondary { background: #FFF0F3; color: #382820; border: 1px solid #F4A6B8; }
    </style>
</head>
<body>
    <main class="card">
        <img src="{{ asset('images/cupdate-logo.svg') }}" alt="CupDate.in" class="logo">
        <h1>404 — Page Not Found</h1>
        <p>The page you requested may have moved or been retired. Explore active coffee dating guides and live connections on CupDate.in.</p>
        <div class="actions">
            <a href="{{ url('/') }}" class="primary">Back to Home</a>
            <a href="{{ url('/discover') }}" class="secondary">Discover</a>
            <a href="{{ url('/cities') }}" class="secondary">Browse Cities</a>
            <a href="{{ url('/blog') }}" class="secondary">Guides &amp; Blog</a>
        </div>
    </main>
</body>
</html>
