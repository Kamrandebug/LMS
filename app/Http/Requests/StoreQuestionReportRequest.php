<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => 'nullable|exists:questions,id',
            'question_set_id' => 'nullable|exists:question_sets,id',
            'report_type' => 'required|in:wrong_answer,typo,wrong_set,confusing_explanation,other',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
