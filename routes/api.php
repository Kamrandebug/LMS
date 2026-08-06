<?php

use App\Http\Controllers\Api\DailyFuelController;
use App\Http\Controllers\Api\QuestionReportController;
use App\Http\Controllers\Api\ProgressController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:60,1')->group(function () {
    // Daily Fuel Quote
    Route::get('/daily-fuel', [DailyFuelController::class, 'index']);

    // Question Report
    Route::post('/report', [QuestionReportController::class, 'store']);

    // Progress Sync
    Route::post('/progress/sync', [ProgressController::class, 'sync']);
});
