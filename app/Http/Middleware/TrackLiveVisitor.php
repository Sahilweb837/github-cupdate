<?php

namespace App\Http\Middleware;

use App\Services\LiveStatsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackLiveVisitor
{
    public function __construct(private readonly LiveStatsService $liveStats) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isMethod('GET')
            && ! $request->ajax()
            && ! $request->expectsJson()
            && ! $request->is(
                'live-stats',
                'admin*',
                'sitemap.xml',
                'robots.txt',
                'live-video/poll',
                'app/messages/*/poll',
                'app/video-call/incoming',
                'app/video-call/*/signals'
            )
        ) {
            $visitorId = $request->hasSession()
                ? $request->session()->getId()
                : sha1(($request->ip() ?? '') . '|' . ($request->userAgent() ?? ''));

            $this->liveStats->recordVisitor($visitorId);
            $this->liveStats->recordVisitDetail($request, $visitorId);
        }

        return $next($request);
    }
}
