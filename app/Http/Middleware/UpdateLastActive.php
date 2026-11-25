<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            if (! $user->last_active || $user->last_active->lt(now()->subMinutes(2))) {
                $user->forceFill([
                    'is_online' => true,
                    'last_active' => now(),
                ])->saveQuietly();
            }
        }

        return $next($request);
    }
}
