<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetThemeFromCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $theme = $request->cookie('theme', 'light');

        // Share theme with views
        view()->share('theme', $theme);

        // Allow Livewire to access it
        if (!session()->has('theme')) {
            session(['theme' => $theme]);
        }

        return $next($request);
    }
}
