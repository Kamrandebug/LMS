<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\QuestionSetController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\MockExamController;
use App\Http\Controllers\Admin\MockExamQuestionController;
use App\Http\Controllers\Admin\QuestionReportController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'auth', 'is_admin'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.alt');

        // Subjects CRUD
        Route::resource('subjects', SubjectController::class)->except(['show']);

        // Topics CRUD
        Route::resource('topics', TopicController::class)->except(['show']);

        // Question Sets CRUD
        Route::resource('question-sets', QuestionSetController::class)->except(['show']);

        // Questions CRUD
        Route::resource('questions', QuestionController::class)->except(['show']);

        // Mock Exams CRUD
        Route::resource('mock-exams', MockExamController::class)->except(['show']);

        // Mock Exam Questions Manager (nested under mock-exams)
        Route::prefix('mock-exams/{mockExam}/questions')->name('mock-exam-questions.')->group(function () {
            Route::get('/',                                                             [MockExamQuestionController::class, 'index'])      ->name('index');
            Route::post('/',                                                            [MockExamQuestionController::class, 'store'])      ->name('store');
            Route::post('/bulk',                                                        [MockExamQuestionController::class, 'bulkStore'])  ->name('bulk-store');
            Route::delete('/{mockExamQuestion}',                                        [MockExamQuestionController::class, 'destroy'])    ->name('destroy');
            Route::patch('/{mockExamQuestion}/order',                                   [MockExamQuestionController::class, 'updateOrder'])->name('update-order');
        });

        // Question Reports (read-only viewer + status management)
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',                       [QuestionReportController::class, 'index'])       ->name('index');
            Route::get('/{report}',               [QuestionReportController::class, 'show'])        ->name('show');
            Route::patch('/{report}/resolve',     [QuestionReportController::class, 'resolve'])     ->name('resolve');
            Route::patch('/{report}/dismiss',     [QuestionReportController::class, 'dismiss'])     ->name('dismiss');
            Route::patch('/{report}/reopen',      [QuestionReportController::class, 'reopen'])      ->name('reopen');
            Route::post('/bulk-resolve',          [QuestionReportController::class, 'bulkResolve']) ->name('bulk-resolve');
            Route::post('/bulk-dismiss',          [QuestionReportController::class, 'bulkDismiss']) ->name('bulk-dismiss');
        });
    });
