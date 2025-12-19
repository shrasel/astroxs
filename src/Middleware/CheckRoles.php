<?php

namespace Shrasel\Astroxs\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoles
{
    /**
     * Handle an incoming request.
     * Allow ANY of the listed roles.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated');
        }

        if (!method_exists($request->user(), 'hasRole')) {
            abort(500, 'User model must use HasAstroxsRoles trait');
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Missing any of required roles: ' . implode(', ', $roles));
    }
}
