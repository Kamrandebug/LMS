<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'question_set_id' => ['required', 'integer', 'exists:question_sets,id'],
            'question'        => ['required', 'string', 'max:2000'],
            'option_a'        => ['required', 'string', 'max:500'],
            'option_b'        => ['required', 'string', 'max:500'],
            'option_c'        => ['required', 'string', 'max:500'],
            'option_d'        => ['required', 'string', 'max:500'],
            'correct_option'  => ['required', Rule::in(['a', 'b', 'c', 'd'])],
            'explanation'     => ['nullable', 'string', 'max:2000'],
            'sort_order'      => ['nullable', 'integer', 'min:0'],
            'is_active'       => ['boolean'],
            'qid'             => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'question_set_id.required' => 'Please select a question set.',
            'question_set_id.exists'   => 'Selected question set does not exist.',
            'question.required'        => 'Question text is required.',
            'correct_option.required'  => 'Please select the correct answer (A, B, C, or D).',
            'correct_option.in'        => 'Correct answer must be one of: A, B, C, D.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'  => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order') !== '' ? (int) $this->input('sort_order') : null,
        ]);
    }
}
