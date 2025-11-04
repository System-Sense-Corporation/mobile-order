<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $routeName = $permission ?: $request->route()?->getName();

        if (! $routeName) {
            return $next($request);
        }

        if ($user->hasPermission($routeName)) {
            return $next($request);
        }

        abort(403);
    }
}
