<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subject;

class PracticeController extends Controller
{
    /**
     * Topics with at least one active question set for practice.
     */
    public function topics(Subject $subject)
    {
        abort_unless($subject->is_active, 404);

        $topics = $subject->topics()
            ->where('is_active', true)
            ->whereHas('questionSets', fn($q) => $q->where('is_active', true))
            ->withCount(['questionSets' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('pages.practice-topics', compact('subject', 'topics'));
    }
}
