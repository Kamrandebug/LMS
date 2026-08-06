<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StreakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function sync(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'completed_sets' => 'nullable|array',
            'completed_sets.*' => 'string|max:120',
            'streak' => 'nullable|integer|min:0|max:999',
        ]);

        // Sync streak
        if (isset($validated['streak'])) {
            $streakService = app(StreakService::class);
            $streakService->updateFromLocalStorage($validated['streak']);
        }

        // If user is authenticated, save progress to DB
        if ($request->user() && isset($validated['completed_sets'])) {
            $request->user()->update([
                'completed_set_ids' => $validated['completed_sets'],
            ]);
        }

        return response()->json([
            'message' => 'Progress synced successfully',
        ]);
    }
}
