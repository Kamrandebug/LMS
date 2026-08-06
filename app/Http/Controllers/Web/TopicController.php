<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use Artesaos\SEOTools\Facades\SEOTools;

class TopicController extends Controller
{
    public function show(Subject $subject, Topic $topic)
    {
        if (!$topic->is_active || $topic->subject_id !== $subject->id) {
            abort(404);
        }

        $topic->load(['questionSets' => fn($q) => $q->active()->orderBy('set_number')]);

        SEOTools::setTitle("{$topic->name} — {$subject->name} MCQs");
        SEOTools::setDescription("Practice {$topic->name} MCQs for {$subject->name} — LAT (Law Admission Test). Free online quiz with 20 questions per set.");

        return view('pages.topic', compact('subject', 'topic'));
    }
}
