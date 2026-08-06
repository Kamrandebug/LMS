<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\QuestionSet;
use App\Models\Question;
use App\Models\MockExam;
use App\Models\MockExamQuestion;
use App\Models\QuestionReport;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'subjects'        => Subject::count(),
            'topics'          => Topic::count(),
            'question_sets'   => QuestionSet::count(),
            'questions'       => Question::count(),
            'mock_exams'      => MockExam::count(),
            'mock_questions'  => MockExamQuestion::count(),
            'pending_reports' => QuestionReport::where('status', 'pending')->count(),
            'total_users'     => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
