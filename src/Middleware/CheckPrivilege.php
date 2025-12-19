<?php

namespace Shrasel\Astroxs\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPrivilege
{
    /**
     * Handle an incoming request.
     * Require ALL listed privileges.
     */
    public function handle(Request $request, Closure $next, ...$privileges): Response
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated');
        }

        if (!method_exists($request->user(), 'hasPrivilege')) {
            abort(500, 'User model must use HasAstroxsRoles trait');
        }

        foreach ($privileges as $privilege) {
            if (!$request->user()->hasPrivilege($privilege)) {
                abort(403, "Missing required privilege: {$privilege}");
            }
        }

        return $next($request);
    }
}
