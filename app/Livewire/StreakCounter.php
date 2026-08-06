<?php

namespace App\Livewire;

use App\Services\StreakService;
use Livewire\Component;

class StreakCounter extends Component
{
    public int $streak = 0;

    public function mount(): void
    {
        $this->streak = app(StreakService::class)->getStreak();
    }

    public function render()
    {
        return view('livewire.streak-counter');
    }
}
