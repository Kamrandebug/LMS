<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\QuizProgressService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class SyncUserProgress implements ShouldQueue
{
    use Dispatchable, Queueable;

    public User $user;
    public array $completedSetIds;

    public function __construct(User $user, array $completedSetIds)
    {
        $this->user = $user;
        $this->completedSetIds = $completedSetIds;
    }

    public function handle(QuizProgressService $progressService): void
    {
        $progressService->syncToUser($this->user, $this->completedSetIds);
    }
}
