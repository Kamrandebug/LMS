<?php

namespace App\Livewire;

use Livewire\Component;

class ThemeToggle extends Component
{
    public bool $isDark = false;

    public function mount(): void
    {
        $this->isDark = request()->cookie('theme', 'light') === 'dark';
    }

    public function toggle(): void
    {
        $this->isDark = !$this->isDark;
        $theme = $this->isDark ? 'dark' : 'light';

        cookie()->queue(cookie('theme', $theme, 60 * 24 * 365));
        session(['theme' => $theme]);

        $this->dispatch('theme-changed', theme: $theme);
    }

    public function render()
    {
        return view('livewire.theme-toggle');
    }
}
