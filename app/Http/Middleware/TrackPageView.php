<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && !$request->ajax() && !$request->expectsJson()) {
            $path = $request->path();
            $cacheKey = 'page_view_' . str_replace('/', '_', $path) . '_' . today()->format('Ymd');

            Cache::increment($cacheKey);
        }

        return $response;
    }
}
