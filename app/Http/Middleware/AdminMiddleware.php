<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Administrator access required.']);
        }

        if ($user->status === 'suspended') {
            auth()->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'This administrator account is suspended.']);
        }

        if ($roles !== [] && ! $user->hasAdminRole(...$roles)) {
            abort(403, 'You do not have permission for this admin section.');
        }

        return $next($request);
    }
}
