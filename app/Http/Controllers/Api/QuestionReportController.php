<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuestionReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionReportController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question_id' => 'nullable|exists:questions,id',
            'mock_exam_question_id' => 'nullable|exists:mock_exam_questions,id',
            'question_set_id' => 'nullable|exists:question_sets,id',
            'report_type' => 'required|in:wrong_answer,typo,wrong_set,confusing_explanation,other',
            'description' => 'nullable|string|max:1000',
        ]);

        $report = QuestionReport::create([
            'question_id' => $validated['question_id'] ?? null,
            'mock_exam_question_id' => $validated['mock_exam_question_id'] ?? null,
            'question_set_id' => $validated['question_set_id'] ?? null,
            'report_type' => $validated['report_type'],
            'description' => $validated['description'] ?? null,
            'reporter_ip' => $request->ip(),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Report received. Thank you!',
            'report_id' => $report->id,
        ], 201);
    }
}
