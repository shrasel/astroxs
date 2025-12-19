<?php

namespace Shrasel\Astroxs\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAbility
{
    /**
     * Handle an incoming request.
     * Require ALL listed abilities (roles or privileges).
     */
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated');
        }

        foreach ($abilities as $ability) {
            if (!$request->user()->can($ability)) {
                abort(403, "Missing required ability: {$ability}");
            }
        }

        return $next($request);
    }
}
