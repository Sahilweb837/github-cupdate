<?php

namespace App\Services;

use App\Models\User;
use App\Models\VideoQueue;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Throwable;

class LiveStatsService
{
    private const CACHE_KEY = 'cupdate:live_visitors';
    private const WINDOW_SECONDS = 300; // 5-minute active window

    /**
     * Record a real visitor session heartbeat (no synthetic/fake inflation).
     */
    public function recordVisitor(string $visitorId): void
    {
        if ($visitorId === '') {
            return;
        }

        try {
            $now = time();
            $hashedKey = substr(hash_hmac('sha256', $visitorId, (string) config('app.key')), 0, 24);
            $visitors = Cache::get(self::CACHE_KEY, []);
            if (! is_array($visitors)) {
                $visitors = [];
            }

            $cutoff = $now - self::WINDOW_SECONDS;
            $visitors = array_filter($visitors, static fn ($ts) => is_int($ts) && $ts >= $cutoff);
            $visitors[$hashedKey] = $now;

            Cache::put(self::CACHE_KEY, $visitors, self::WINDOW_SECONDS);
        } catch (Throwable) {
            // Keep requests resilient if cache driver is unavailable
        }
    }

    /**
     * Record real visitor location, device, landing page, and referrer for Admin analytics.
     */
    public function recordVisitDetail(Request $request, string $visitorId): void
    {
        try {
            if (! Schema::hasTable('visitor_logs')) {
                return;
            }

            $path = '/' . ltrim($request->path(), '/');
            if ($path !== '/') {
                $path = rtrim($path, '/');
            }
            $path = mb_substr($path, 0, 255);

            // Throttle duplicate DB writes for the same visitor & page within 60 seconds
            $throttleKey = 'cupdate:vhit:' . sha1($visitorId . '|' . $path);
            if (Cache::has($throttleKey)) {
                return;
            }
            Cache::put($throttleKey, 1, 60);

            $ip = (string) ($request->ip() ?? '');
            $geo = $this->resolveLocation($request, $ip);
            $user = $request->user();

            if ((! $geo['city'] || $geo['city'] === 'Unknown') && $user && $user->city) {
                $geo['city'] = $user->city;
                $geo['region'] = $geo['region'] ?: ($user->state ?? '');
                $geo['country'] = $geo['country'] ?: ($user->country === 'IN' ? 'India' : ($user->country ?? 'India'));
            }

            $referrerHost = 'Direct';
            $rawRef = (string) $request->headers->get('referer', '');
            if ($rawRef !== '') {
                $parsedHost = parse_url($rawRef, PHP_URL_HOST);
                if ($parsedHost && ! str_contains($parsedHost, 'cupdate.in')) {
                    $referrerHost = mb_substr($parsedHost, 0, 120);
                } elseif ($parsedHost) {
                    $referrerHost = 'Internal';
                }
            }

            $hashedSession = hash_hmac('sha256', $visitorId, (string) config('app.key'));
            $hashedIp = $ip !== ''
                ? 'h_' . substr(hash_hmac('sha256', $ip, (string) config('app.key')), 0, 16)
                : null;

            VisitorLog::create([
                'session_id' => $hashedSession,
                'user_id' => $user?->id,
                'ip_address' => $hashedIp,
                'country' => mb_substr($geo['country'] ?: 'Unknown', 0, 80),
                'region' => mb_substr($geo['region'] ?: 'Unknown', 0, 100),
                'city' => mb_substr($geo['city'] ?: 'Unknown', 0, 100),
                'path' => $path,
                'referrer' => $referrerHost,
                'device_type' => $this->detectDevice((string) $request->userAgent()),
                'last_seen_at' => now(),
                'created_at' => now(),
            ]);
        } catch (Throwable) {
            // Never interrupt user requests if analytics logging fails
        }
    }

    /**
     * Return real-time counts from active sessions, online members, and live video queue.
     *
     * @return array{active_visitors: int, online_members: int, live_video_active: int, total_members: int}
     */
    public function snapshot(): array
    {
        $activeVisitors = 0;
        try {
            $now = time();
            $cutoff = $now - self::WINDOW_SECONDS;
            $visitors = Cache::get(self::CACHE_KEY, []);
            if (is_array($visitors)) {
                $visitors = array_filter($visitors, static fn ($ts) => is_int($ts) && $ts >= $cutoff);
                $activeVisitors = count($visitors);
            }

            if ($activeVisitors === 0 && Schema::hasTable('sessions')) {
                $activeVisitors = (int) DB::table('sessions')
                    ->where('last_activity', '>=', $cutoff)
                    ->count();
            }
        } catch (Throwable) {
            $activeVisitors = 0;
        }

        $onlineMembers = 0;
        $totalMembers = 0;
        $liveVideoActive = 0;

        try {
            $onlineMembers = (int) User::where('status', 'active')
                ->where('is_admin', false)
                ->where('email', 'not like', '%@cupdate.in')
                ->where(function ($q) {
                    $q->where('is_online', true)
                        ->orWhere('last_active', '>=', now()->subMinutes(10));
                })
                ->count();

            $totalMembers = (int) User::where('status', 'active')
                ->where('is_admin', false)
                ->where('email', 'not like', '%@cupdate.in')
                ->count();
        } catch (Throwable) {
            // fallback 0
        }

        try {
            if (Schema::hasTable('video_queue')) {
                $liveVideoActive = (int) VideoQueue::where('status', 'waiting')
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->count();
            }
        } catch (Throwable) {
            $liveVideoActive = 0;
        }

        return [
            'active_visitors' => max($activeVisitors, $onlineMembers > 0 ? 1 : 0),
            'online_members' => $onlineMembers,
            'live_video_active' => $liveVideoActive,
            'total_members' => $totalMembers,
        ];
    }

    /**
     * Real visitor analytics and location breakdowns for the Admin Dashboard.
     *
     * @return array<string, mixed>
     */
    public function adminAnalytics(string $range = 'all'): array
    {
        $empty = [
            'range' => $range,
            'today_visits' => 0,
            'today_unique' => 0,
            'yesterday_visits' => 0,
            'visits_7d' => 0,
            'visits_30d' => 0,
            'total_visits' => 0,
            'total_unique' => 0,
            'range_visits' => 0,
            'range_unique' => 0,
            'member_visits' => 0,
            'guest_visits' => 0,
            'daily_series' => collect(),
            'top_locations' => collect(),
            'top_pages' => collect(),
            'top_referrers' => collect(),
            'device_breakdown' => collect(),
            'recent_visitors' => collect(),
        ];

        try {
            if (! Schema::hasTable('visitor_logs')) {
                return $empty;
            }

            $todayStart = now()->startOfDay();
            $yesterdayStart = now()->subDay()->startOfDay();
            $sevenDaysAgo = now()->subDays(6)->startOfDay();
            $thirtyDaysAgo = now()->subDays(29)->startOfDay();

            $todayVisits = (int) VisitorLog::where('created_at', '>=', $todayStart)->count();
            $todayUnique = (int) VisitorLog::where('created_at', '>=', $todayStart)->distinct('session_id')->count('session_id');
            $yesterdayVisits = (int) VisitorLog::whereBetween('created_at', [$yesterdayStart, $todayStart])->count();
            $visits7d = (int) VisitorLog::where('created_at', '>=', $sevenDaysAgo)->count();
            $visits30d = (int) VisitorLog::where('created_at', '>=', $thirtyDaysAgo)->count();
            $totalVisits = (int) VisitorLog::count();
            $totalUnique = (int) VisitorLog::distinct('session_id')->count('session_id');

            $since = match ($range) {
                'today' => $todayStart,
                '7d' => $sevenDaysAgo,
                '30d' => $thirtyDaysAgo,
                default => null,
            };

            $scoped = VisitorLog::query()->when($since, fn ($q) => $q->where('created_at', '>=', $since));

            $rangeVisits = (int) (clone $scoped)->count();
            $rangeUnique = (int) (clone $scoped)->distinct('session_id')->count('session_id');
            $memberVisits = (int) (clone $scoped)->whereNotNull('user_id')->count();
            $guestVisits = max(0, $rangeVisits - $memberVisits);

            $rawDaily = VisitorLog::where('created_at', '>=', $sevenDaysAgo)
                ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as visits'), DB::raw('COUNT(DISTINCT session_id) as unique_visitors'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->get()
                ->keyBy('day');

            $dailySeries = collect();
            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i);
                $key = $d->format('Y-m-d');
                $row = $rawDaily->get($key);
                $dailySeries->push([
                    'label' => $d->format('M j'),
                    'visits' => (int) ($row->visits ?? 0),
                    'unique' => (int) ($row->unique_visitors ?? 0),
                ]);
            }

            $topLocations = (clone $scoped)->select('city', 'region', 'country', DB::raw('COUNT(*) as visits'), DB::raw('COUNT(DISTINCT session_id) as unique_visitors'))
                ->groupBy('city', 'region', 'country')
                ->orderByDesc('visits')
                ->take(12)
                ->get();

            $topPages = (clone $scoped)->select('path', DB::raw('COUNT(*) as visits'), DB::raw('COUNT(DISTINCT session_id) as unique_visitors'))
                ->groupBy('path')
                ->orderByDesc('visits')
                ->take(12)
                ->get();

            $topReferrers = (clone $scoped)->select('referrer', DB::raw('COUNT(*) as visits'))
                ->groupBy('referrer')
                ->orderByDesc('visits')
                ->take(8)
                ->get();

            $deviceBreakdown = (clone $scoped)->select('device_type', DB::raw('COUNT(*) as visits'))
                ->groupBy('device_type')
                ->orderByDesc('visits')
                ->get();

            $recentVisitors = VisitorLog::with('user:id,full_name,email,city')
                ->latest('created_at')
                ->take(20)
                ->get();

            return [
                'range' => $range,
                'today_visits' => $todayVisits,
                'today_unique' => $todayUnique,
                'yesterday_visits' => $yesterdayVisits,
                'visits_7d' => $visits7d,
                'visits_30d' => $visits30d,
                'total_visits' => $totalVisits,
                'total_unique' => $totalUnique,
                'range_visits' => $rangeVisits,
                'range_unique' => $rangeUnique,
                'member_visits' => $memberVisits,
                'guest_visits' => $guestVisits,
                'daily_series' => $dailySeries,
                'top_locations' => $topLocations,
                'top_pages' => $topPages,
                'top_referrers' => $topReferrers,
                'device_breakdown' => $deviceBreakdown,
                'recent_visitors' => $recentVisitors,
            ];
        } catch (Throwable) {
            return $empty;
        }
    }

    /**
     * Real-time online and recently active non-admin members grouped by city.
     */
    public function onlineUsersByCity(int $limit = 12): \Illuminate\Support\Collection
    {
        try {
            return User::where('status', 'active')
                ->where('is_admin', false)
                ->where('email', 'not like', '%@cupdate.in')
                ->where(function ($q) {
                    $q->where('is_online', true)
                        ->orWhere('last_active', '>=', now()->subMinutes(10));
                })
                ->select('city', 'state', 'country', DB::raw('COUNT(*) as online_total'))
                ->groupBy('city', 'state', 'country')
                ->orderByDesc('online_total')
                ->take($limit)
                ->get();
        } catch (Throwable) {
            return collect();
        }
    }

    /**
     * Resolve location from edge/proxy headers first, then cached 24h IP lookup for public IPs.
     *
     * @return array{city: string, region: string, country: string}
     */
    private function resolveLocation(Request $request, string $ip): array
    {
        $city = (string) ($request->header('X-Vercel-IP-City')
            ?? $request->header('X-Geo-City')
            ?? $request->header('CF-IPCity')
            ?? $request->header('X-City')
            ?? '');
        $region = (string) ($request->header('X-Vercel-IP-Country-Region')
            ?? $request->header('X-Geo-Region')
            ?? $request->header('X-Region')
            ?? '');
        $country = (string) ($request->header('CF-IPCountry')
            ?? $request->header('X-Vercel-IP-Country')
            ?? $request->header('X-Country-Code')
            ?? '');

        if ($country === 'IN') {
            $country = 'India';
        }

        if ($city !== '' && $country !== '') {
            return ['city' => urldecode($city), 'region' => urldecode($region), 'country' => $country];
        }

        $isPublicIp = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
        if (! $isPublicIp || app()->runningUnitTests()) {
            return [
                'city' => $city ?: 'Local / Direct',
                'region' => $region ?: 'Local',
                'country' => $country ?: 'India',
            ];
        }

        $cacheKey = 'cupdate:geo:' . md5($ip);
        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $resolved = ['city' => $city ?: 'Unknown', 'region' => $region ?: 'Unknown', 'country' => $country ?: 'Unknown'];

        try {
            $resp = Http::timeout(1.5)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,country,regionName,city',
            ]);
            if ($resp->ok() && ($resp->json('status') === 'success')) {
                $resolved = [
                    'city' => (string) ($resp->json('city') ?: ($city ?: 'Unknown')),
                    'region' => (string) ($resp->json('regionName') ?: ($region ?: 'Unknown')),
                    'country' => (string) ($resp->json('country') ?: ($country ?: 'Unknown')),
                ];
            }
        } catch (Throwable) {
            // Non-blocking fallback
        }

        Cache::put($cacheKey, $resolved, 86400);

        return $resolved;
    }

    private function detectDevice(string $ua): string
    {
        if (preg_match('/ipad|tablet|playbook|silk/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile/i', $ua)) {
            return 'Mobile';
        }

        return 'Desktop';
    }
}
