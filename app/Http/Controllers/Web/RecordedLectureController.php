<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;

class RecordedLectureController extends Controller
{
    /**
     * All active topics for this subject (always shown, even with zero lectures).
     */
    public function topics(Subject $subject)
    {
        abort_unless($subject->is_active, 404);

        $topics = $subject->topics()
            ->where('is_active', true)
            ->withCount(['recordedLectures' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('pages.lecture-topics', compact('subject', 'topics'));
    }

    /**
     * All active recorded lectures for a specific topic.
     */
    public function show(Subject $subject, Topic $topic)
    {
        abort_unless($subject->is_active, 404);
        abort_unless($topic->is_active && $topic->subject_id === $subject->id, 404);

        $lectures = $topic->recordedLectures()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.recorded-lectures', compact('subject', 'topic', 'lectures'));
    }
}
