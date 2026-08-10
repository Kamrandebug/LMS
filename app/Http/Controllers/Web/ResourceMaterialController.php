<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;

class ResourceMaterialController extends Controller
{
    /**
     * All active topics for this subject (always shown, even with zero resources).
     */
    public function topics(Subject $subject)
    {
        abort_unless($subject->is_active, 404);

        $topics = $subject->topics()
            ->where('is_active', true)
            ->withCount(['resourceMaterials' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('pages.resource-topics', compact('subject', 'topics'));
    }

    /**
     * All active resource materials for a specific topic.
     */
    public function show(Subject $subject, Topic $topic)
    {
        abort_unless($subject->is_active, 404);
        abort_unless($topic->is_active && $topic->subject_id === $subject->id, 404);

        $materials = $topic->resourceMaterials()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.resource-materials', compact('subject', 'topic', 'materials'));
    }
}
