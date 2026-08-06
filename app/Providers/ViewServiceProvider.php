<?php

namespace App\Providers;

use App\Models\Subject;
use App\Models\DailyQuote;
use App\Services\DailyFuelService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DailyFuelService::class);
    }

    public function boot(): void
    {
        // Share navigation subjects globally
        View::composer('*', function ($view) {
            $subjects = Cache::remember('subjects.nav', 3600, function () {
                return Subject::active()
                    ->withCount(['topics' => function ($q) {
                        $q->active();
                    }])
                    ->get();
            });

            $view->with('navSubjects', $subjects);
        });

        // Share contact info globally
        View::composer('*', function ($view) {
            $view->with('contactInfo', config('learnup.contact'));
        });
    }
}
