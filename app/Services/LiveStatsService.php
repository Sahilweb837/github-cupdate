<?php

namespace App\Services;

use App\Models\User;
use App\Models\VideoQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
            $visitors = Cache::get(self::CACHE_KEY, []);
            if (! is_array($visitors)) {
                $visitors = [];
            }

            $cutoff = $now - self::WINDOW_SECONDS;
            $visitors = array_filter($visitors, static fn ($ts) => is_int($ts) && $ts >= $cutoff);
            $visitors[$visitorId] = $now;

            Cache::put(self::CACHE_KEY, $visitors, self::WINDOW_SECONDS);
        } catch (Throwable) {
            // Keep requests resilient if cache driver is unavailable
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
                ->where(function ($q) {
                    $q->where('is_online', true)
                        ->orWhere('last_active', '>=', now()->subMinutes(10));
                })
                ->count();

            $totalMembers = (int) User::where('status', 'active')
                ->where('is_admin', false)
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
}
