<?php

namespace App\Livewire;

use App\Services\DailyFuelService;
use Livewire\Component;

class DailyFuelBanner extends Component
{
    public ?string $quote = null;
    public ?string $author = null;
    public string $type = 'motivation';
    public bool $visible = true;

    public function mount(): void
    {
        try {
            $service = app(DailyFuelService::class);
            $dailyQuote = $service->getTodaysQuote();
            $this->quote = $dailyQuote->quote;
            $this->author = $dailyQuote->author;
            $this->type = $dailyQuote->type;
        } catch (\Exception $e) {
            $this->quote = 'Start where you are. Use what you have. Do what you can.';
            $this->type = 'motivation';
        }
    }

    public function dismiss(): void
    {
        $this->visible = false;
    }

    public function getBadgeColorProperty(): string
    {
        return match($this->type) {
            'islamic' => 'text-emerald-400',
            'fact' => 'text-amber-400',
            default => 'text-brand-primary',
        };
    }

    public function render()
    {
        return view('livewire.daily-fuel-banner');
    }
}
