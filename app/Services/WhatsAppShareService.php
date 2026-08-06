<?php

namespace App\Services;

class WhatsAppShareService
{
    public function buildShareUrl(string $message): string
    {
        $baseUrl = config('learnup.whatsapp.share_base_url');
        return $baseUrl . '?' . http_build_query(['text' => $message]);
    }

    public function buildQuizShareMessage(string $setName, int $score, int $total): string
    {
        $emoji = $score === $total ? '🎯🔥' : '🎯';
        $url = config('app.url');
        return "{$emoji} I scored {$score}/{$total} on LearnUp! Topic: {$setName}. Can you beat me? Practice here: {$url}";
    }
}
