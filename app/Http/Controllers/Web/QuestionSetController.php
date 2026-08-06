<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\QuestionSet;
use App\Models\Subject;
use App\Models\Topic;
use Artesaos\SEOTools\Facades\SEOTools;

class QuestionSetController extends Controller
{
    public function show(Subject $subject, Topic $topic, int $setNumber)
    {
        $set = QuestionSet::where('topic_id', $topic->id)
            ->where('set_number', $setNumber)
            ->where('is_active', true)
            ->with('questions')
            ->firstOrFail();

        // Verify topic belongs to subject
        if ($topic->subject_id !== $subject->id) {
            abort(404);
        }

        SEOTools::setTitle("Set {$setNumber} — {$topic->name} | {$subject->name} MCQs");
        SEOTools::setDescription("{$subject->name} MCQs: {$topic->name} Set {$setNumber}. Practice 20 questions with explanations.");

        return view('pages.question-set', compact('subject', 'topic', 'set'));
    }
}
