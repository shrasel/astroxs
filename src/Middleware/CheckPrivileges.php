<?php

namespace Shrasel\Astroxs\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPrivileges
{
    /**
     * Handle an incoming request.
     * Allow ANY of the listed privileges.
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
            if ($request->user()->hasPrivilege($privilege)) {
                return $next($request);
            }
        }

        abort(403, 'Missing any of required privileges: ' . implode(', ', $privileges));
    }
}
