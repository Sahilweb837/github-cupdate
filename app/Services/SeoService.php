<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class SeoService
{
    public static function baseUrl(): string
    {
        return rtrim((string) config('seo.canonical_url', 'https://cupdate.in'), '/');
    }

    public static function canonicalForPath(string $path = '/'): string
    {
        $clean = '/' . ltrim($path, '/');
        $clean = $clean === '/' ? '/' : rtrim($clean, '/');

        return self::baseUrl() . ($clean === '/' ? '' : $clean);
    }

    public static function canonicalForRequest(Request $request, ?string $override = null): string
    {
        if ($override) {
            return $override;
        }

        $path = $request->getPathInfo() ?: '/';
        $page = (int) $request->query('page', 1);

        $base = self::canonicalForPath($path);

        return $page > 1 ? $base . '?page=' . $page : $base;
    }

    public function sitemapEntries(): array
    {
        $today = now()->toAtomString();

        $entries = [
            ['loc' => self::canonicalForPath('/'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '1.0', 'image' => self::baseUrl() . '/images/hero-coffee-couple.jpg', 'image_title' => 'CupDate — Meet Someone. Share a Coffee. Create a Connection.'],
            ['loc' => self::canonicalForPath('/discover'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/how-it-works'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => self::canonicalForPath('/safety'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => self::canonicalForPath('/about-us'), 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => self::canonicalForPath('/contact'), 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => self::canonicalForPath('/blog'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.8'],
            ['loc' => self::canonicalForPath('/dating-in-himachal-pradesh'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/meet-singles-in-kangra'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/meet-singles-in-dharamshala'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/meet-singles-in-mandi'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/coffee-date-ideas-in-himachal-pradesh'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9', 'image' => self::baseUrl() . '/images/cafe-blue-bottle.jpg', 'image_title' => 'Coffee Date Ideas in Himachal Pradesh'],
            ['loc' => self::canonicalForPath('/privacy-policy'), 'lastmod' => $today, 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => self::canonicalForPath('/terms-and-conditions'), 'lastmod' => $today, 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => self::canonicalForPath('/community-guidelines'), 'lastmod' => $today, 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => self::canonicalForPath('/safety-center'), 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.5'],
        ];

        $posts = BlogPost::where('is_published', true)->latest('published_at')->get();
        foreach ($posts as $post) {
            $entries[] = [
                'loc' => self::canonicalForPath('/blog/' . $post->slug),
                'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
                'image' => $post->cover_image ? self::baseUrl() . '/' . ltrim($post->cover_image, '/') : null,
                'image_title' => $post->title,
            ];
        }

        return $entries;
    }

    public function renderSitemapXml(): string
    {
        return view('pages.sitemap', ['entries' => $this->sitemapEntries()])->render();
    }

    public function renderRobotsTxt(): string
    {
        $sitemapUrl = self::canonicalForPath('/sitemap.xml');

        return implode("\n", [
            'User-agent: *',
            'Allow: /',
            '',
            '# Block private member, admin, and auth endpoints',
            'Disallow: /admin',
            'Disallow: /admin/',
            'Disallow: /app',
            'Disallow: /app/',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            'Disallow: /auth/',
            'Disallow: /guest',
            '',
            '# Prevent duplicate filtered/search parameter crawling',
            'Disallow: /*?filter=',
            'Disallow: /*?sort=',
            'Disallow: /*?q=',
            'Disallow: /*?city=',
            'Disallow: /*?interest=',
            '',
            'Sitemap: ' . $sitemapUrl,
            '',
        ]);
    }

    public function writeStaticFiles(): void
    {
        file_put_contents(public_path('sitemap.xml'), $this->renderSitemapXml());
        file_put_contents(public_path('robots.txt'), $this->renderRobotsTxt());
    }
}
