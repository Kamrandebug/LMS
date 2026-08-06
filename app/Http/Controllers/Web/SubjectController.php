<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Cache;

class SubjectController extends Controller
{
    public function show(Subject $subject)
    {
        if (!$subject->is_active) {
            abort(404);
        }

        $subject->load(['topics' => fn($q) => $q->active()
            ->with(['questionSets' => fn($q) => $q->active()->orderBy('set_number')])
        ]);

        SEOTools::setTitle("{$subject->name} MCQs");
        SEOTools::setDescription("Practice {$subject->name} MCQs for LAT (Law Admission Test). Free online quiz with explanations.");
        SEOTools::opengraph()->setUrl(route('subjects.show', $subject));

        return view('pages.subject', compact('subject'));
    }
}
