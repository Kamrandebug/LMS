<?php

namespace App\Services;

class StreakService
{
    public function getStreak(): int
    {
        return (int) session('streak', 0);
    }

    public function incrementStreak(): int
    {
        $streak = $this->getStreak() + 1;
        session(['streak' => $streak]);
        return $streak;
    }

    public function resetStreak(): void
    {
        session(['streak' => 0]);
    }

    public function updateFromLocalStorage(int $clientStreak): int
    {
        // Accept whatever the client sends if higher
        $current = $this->getStreak();
        if ($clientStreak > $current) {
            session(['streak' => $clientStreak]);
            return $clientStreak;
        }
        return $current;
    }
}
