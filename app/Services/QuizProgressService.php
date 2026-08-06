<?php

namespace App\Services;

class QuizProgressService
{
    private const SESSION_PREFIX = 'quiz_v2_';

    public function saveProgress(string $sessionKey, array $state): void
    {
        $fullKey = $this->getKey($sessionKey);
        session([$fullKey => $state]);
    }

    public function loadProgress(string $sessionKey): ?array
    {
        $fullKey = $this->getKey($sessionKey);
        return session($fullKey);
    }

    public function clearProgress(string $sessionKey): void
    {
        $fullKey = $this->getKey($sessionKey);
        session()->forget($fullKey);
    }

    public function hasProgress(string $sessionKey): bool
    {
        $fullKey = $this->getKey($sessionKey);
        return session()->has($fullKey);
    }

    public function syncToUser(\App\Models\User $user, array $completedSetIds): void
    {
        // When user logs in, persist their progress
        $user->update([
            'completed_sets' => $completedSetIds,
        ]);
    }

    private function getKey(string $sessionKey): string
    {
        return self::SESSION_PREFIX . $sessionKey;
    }
}
