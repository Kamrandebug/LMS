<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// Middleware aliases
App::make('router')->aliasMiddleware('theme.cookie', \App\Http\Middleware\SetThemeFromCookie::class);
App::make('router')->aliasMiddleware('track.pageview', \App\Http\Middleware\TrackPageView::class);

// Broadcast channel placeholder
Broadcast::channel('App.Models.User.{id}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
