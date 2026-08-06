<?php

namespace App\Services;

use App\Models\DailyQuote;
use Illuminate\Support\Facades\Cache;

class DailyFuelService
{
    public function getTodaysQuote(): DailyQuote
    {
        $cacheKey = 'daily_fuel_' . today()->format('Y-m-d');

        return Cache::remember($cacheKey, now()->endOfDay(), function () {
            $isFriday = today()->isFriday();

            return DailyQuote::active()
                ->when($isFriday, fn($q) => $q->where('type', 'islamic'))
                ->inRandomOrder()
                ->firstOrFail();
        });
    }

    public function getBadgeColor(string $type): string
    {
        return match ($type) {
            'islamic' => 'text-emerald-400',
            'fact' => 'text-amber-400',
            default => 'text-brand-primary',
        };
    }

    public function getTypeEmoji(string $type): string
    {
        return match ($type) {
            'islamic' => '📖',
            'fact' => '🔬',
            default => '⚡',
        };
    }
}
