<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MockExam;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Cache;

class MockExamController extends Controller
{
    public function index()
    {
        $mockExams = Cache::remember('mock_exams.active', 1800, function () {
            return MockExam::where('is_active', true)
                ->orderBy('exam_type')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        SEOTools::setTitle('Mock Test Series');
        SEOTools::setDescription('Timed mock tests for LAT (Law Admission Test). Practice under real exam conditions.');

        return view('pages.mock-tests', compact('mockExams'));
    }

    public function show(MockExam $mockExam)
    {
        if (!$mockExam->is_active) {
            abort(404);
        }

        $mockExam->load(['questions' => fn($q) => $q->orderBy('sort_order')]);

        // Increment attempt count
        $mockExam->increment('attempt_count');

        SEOTools::setTitle($mockExam->name);
        SEOTools::setDescription("{$mockExam->name} — {$mockExam->total_questions} questions, {$mockExam->duration_minutes} minutes.");

        return view('pages.mock-test-show', compact('mockExam'));
    }
}
