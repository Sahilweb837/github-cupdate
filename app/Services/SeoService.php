<?php

namespace App\Services;

use App\Data\SeoCities;
use App\Data\SeoGuides;
use App\Models\BlogPost;
use App\Models\Moment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

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
            ['loc' => self::canonicalForPath('/'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '1.0', 'image' => self::baseUrl() . '/images/cupdate-scene.svg', 'image_title' => 'CupDate Free Coffee & CupTV Live Video Dating Website'],
            ['loc' => self::canonicalForPath('/discover'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/live-video'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.95'],
            ['loc' => self::canonicalForPath('/moments'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/cafes'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/cities'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/truth-and-dare'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/travel-groups'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/icebreaker-questions'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/weekend-meetups'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/speed-dating'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/couple-activities'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/college-dating-india'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/solo-travel-companions'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/dating-etiquette-india'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/how-it-works'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => self::canonicalForPath('/safety'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => self::canonicalForPath('/about-us'), 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => self::canonicalForPath('/contact'), 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => self::canonicalForPath('/blog'), 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.85'],
            ['loc' => self::canonicalForPath('/rishta'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => self::canonicalForPath('/community/lgbtq'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => self::canonicalForPath('/dating-in-himachal-pradesh'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/meet-singles-in-kangra'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/meet-singles-in-dharamshala'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/meet-singles-in-mandi'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/coffee-date-ideas-in-himachal-pradesh'), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => self::canonicalForPath('/privacy-policy'), 'lastmod' => $today, 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => self::canonicalForPath('/terms-and-conditions'), 'lastmod' => $today, 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => self::canonicalForPath('/community-guidelines'), 'lastmod' => $today, 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => self::canonicalForPath('/safety-center'), 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.5'],
        ];

        $seenLocs = array_flip(array_column($entries, 'loc'));

        foreach (SeoGuides::all() as $guide) {
            $loc = self::canonicalForPath('/blog/' . $guide['slug']);
            if (! isset($seenLocs[$loc])) {
                $seenLocs[$loc] = true;
                $entries[] = [
                    'loc' => $loc,
                    'lastmod' => $today,
                    'changefreq' => 'weekly',
                    'priority' => '0.85',
                    'image' => self::baseUrl() . '/images/cupdate-scene.svg',
                    'image_title' => $guide['h1'],
                ];
            }
        }

        foreach (SeoCities::all() as $city) {
            $loc = self::canonicalForPath('/dating-in/' . $city['slug']);
            if (! isset($seenLocs[$loc])) {
                $seenLocs[$loc] = true;
                $entries[] = [
                    'loc' => $loc,
                    'lastmod' => $today,
                    'changefreq' => 'weekly',
                    'priority' => '0.85',
                ];
            }
        }

        try {
            $posts = BlogPost::where('is_published', true)->latest('published_at')->get();
            foreach ($posts as $post) {
                $loc = self::canonicalForPath('/blog/' . $post->slug);
                if (! isset($seenLocs[$loc])) {
                    $seenLocs[$loc] = true;
                    $entries[] = [
                        'loc' => $loc,
                        'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.75',
                        'image' => $post->cover_image ? self::baseUrl() . '/' . ltrim($post->cover_image, '/') : null,
                        'image_title' => $post->title,
                    ];
                }
            }
        } catch (Throwable) {
            // Keep static SEO entries resilient if DB is unavailable during CLI generation
        }

        try {
            if (Schema::hasTable('posts')) {
                $moments = Moment::where('is_approved', true)->where('is_flagged', false)->latest('created_at')->take(50)->get();
                foreach ($moments as $moment) {
                    $entries[] = [
                        'loc' => self::canonicalForPath('/moments/' . $moment->id),
                        'lastmod' => ($moment->created_at ?? now())->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                        'image' => $moment->image_url ? self::baseUrl() . '/' . ltrim($moment->image_url, '/') : null,
                        'image_title' => 'CupDate Moment' . ($moment->city ? ' in ' . $moment->city : ''),
                    ];
                }
            }
        } catch (Throwable) {
            // Ignore when DB is offline
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

        $aiBots = [
            'Googlebot',
            'Google-Extended',
            'Bingbot',
            'GPTBot',
            'OAI-SearchBot',
            'ChatGPT-User',
            'ClaudeBot',
            'Claude-Web',
            'anthropic-ai',
            'PerplexityBot',
            'Applebot',
            'Applebot-Extended',
            'CCBot',
        ];

        $lines = [
            '# CupDate (cupdate.in) - Search Engine & AI Discovery Robots Rules',
            'User-agent: *',
            'Allow: /',
            'Allow: /llms.txt',
            '',
            '# Private authenticated & API endpoints only',
            'Disallow: /admin',
            'Disallow: /admin/',
            'Disallow: /app',
            'Disallow: /app/',
            'Disallow: /live-stats',
            'Disallow: /live-video/poll',
            'Disallow: /live-video/signal',
            'Disallow: /live-video/leave',
            '',
            '# Prevent duplicate filtered/search parameter crawling',
            'Disallow: /*?filter=',
            'Disallow: /*?sort=',
            'Disallow: /*?q=',
            'Disallow: /*?interest=',
            '',
        ];

        foreach ($aiBots as $bot) {
            $lines[] = "User-agent: {$bot}";
            $lines[] = 'Allow: /';
            $lines[] = 'Disallow: /admin/';
            $lines[] = 'Disallow: /app/';
            $lines[] = '';
        }

        $lines[] = 'Sitemap: ' . $sitemapUrl;
        $lines[] = '';

        return implode("\n", $lines);
    }

    public function writeStaticFiles(): void
    {
        file_put_contents(public_path('sitemap.xml'), $this->renderSitemapXml());
        file_put_contents(public_path('robots.txt'), $this->renderRobotsTxt());
    }
}
