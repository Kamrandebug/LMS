<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Cache;

class MockTestController extends Controller
{
    public function index()
    {
        $mockTests = Cache::remember('mock_tests.active', 1800, function () {
            return MockTest::where('is_active', true)
                ->orderBy('exam_type')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        SEOTools::setTitle('Mock Test Series');
        SEOTools::setDescription('Timed mock tests for LAT (Law Admission Test). Practice under real exam conditions.');

        return view('pages.mock-tests', compact('mockTests'));
    }

    public function show(MockTest $mockTest)
    {
        if (!$mockTest->is_active) {
            abort(404);
        }

        $mockTest->load(['questions' => fn($q) => $q->orderBy('sort_order')]);

        // Increment attempt count
        $mockTest->increment('attempt_count');

        SEOTools::setTitle($mockTest->name);
        SEOTools::setDescription("{$mockTest->name} — {$mockTest->total_questions} questions, {$mockTest->duration_minutes} minutes.");

        return view('pages.mock-test-show', compact('mockTest'));
    }
}
