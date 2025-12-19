<?php

namespace Shrasel\Astroxs\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAbilities
{
    /**
     * Handle an incoming request.
     * Allow ANY of the listed abilities (roles or privileges).
     */
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated');
        }

        foreach ($abilities as $ability) {
            if ($request->user()->can($ability)) {
                return $next($request);
            }
        }

        abort(403, 'Missing any of required abilities: ' . implode(', ', $abilities));
    }
}
