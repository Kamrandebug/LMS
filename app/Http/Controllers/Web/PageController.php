<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOTools;

class PageController extends Controller
{
    public function privacy()
    {
        SEOTools::setTitle('Privacy Policy');
        SEOTools::setDescription('Privacy Policy for LearnUp MCQ platform.');

        return view('pages.privacy-policy');
    }

    public function terms()
    {
        SEOTools::setTitle('Terms of Service');
        SEOTools::setDescription('Terms of Service for LearnUp MCQ platform.');

        return view('pages.terms-of-service');
    }
}
