<?php

namespace Shrasel\Astroxs\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request and log request/response pairs.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Log the incoming request
        Log::channel(config('astroxs.log_channel', 'stack'))->info('Astroxs Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
            'user_email' => $request->user()?->email,
            'input' => $request->except(['password', 'password_confirmation']),
        ]);

        $response = $next($request);

        // Log the response
        $duration = microtime(true) - $startTime;
        
        Log::channel(config('astroxs.log_channel', 'stack'))->info('Astroxs Response', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'duration' => round($duration * 1000, 2) . 'ms',
            'user_id' => $request->user()?->id,
        ]);

        return $response;
    }
}
