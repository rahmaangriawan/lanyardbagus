<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BypassCsrfForApi
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only bypass for API routes from our Astro app
        if ($request->is('api/auth/*') || $request->is('sanctum/*')) {
            return $next($request);
        }

        return $next($request);
    }
}
