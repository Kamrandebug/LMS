<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\SubjectController;
use App\Http\Controllers\Web\TopicController;
use App\Http\Controllers\Web\QuestionSetController;
use App\Http\Controllers\Web\MockExamController;
use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/mock-tests', [MockExamController::class, 'index'])->name('mock-exams.index');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');

// Protected routes (login required)
Route::middleware('auth')->group(function () {
    // Subjects, Topics, Question Sets
    Route::get('/subjects/{subject:slug}', [SubjectController::class, 'show'])
        ->name('subjects.show');

    Route::get('/subjects/{subject:slug}/{topic:slug}', [TopicController::class, 'show'])
        ->name('topics.show');

    Route::get('/subjects/{subject:slug}/{topic:slug}/set-{setNumber}', [QuestionSetController::class, 'show'])
        ->name('sets.show');

    // Taking a mock test requires login; listing page stays public
    Route::get('/mock-tests/{mockExam:slug}', [MockExamController::class, 'show'])
        ->name('mock-exams.show');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';
