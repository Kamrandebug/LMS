<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Artesaos\SEOTools\Facades\SEOTools;

class HomeController extends Controller
{
    public function index()
    {
        $subjects = Subject::active()
            ->with(['topics' => fn($q) => $q->active()->withCount('questionSets')])
            ->get();

        SEOTools::setTitle('Home');
        SEOTools::setDescription('Free MCQ practice for LAT (Law Admission Test). 3,600+ questions with explanations.');
        SEOTools::opengraph()->setUrl(url('/'));

        return view('pages.home', compact('subjects'));
    }
}
