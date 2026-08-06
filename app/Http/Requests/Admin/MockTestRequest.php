<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MockTestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        $testId = $this->route('mock_test')?->id;

        return [
            'name'             => ['required', 'string', 'max:255'],
            'slug'             => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('mock_exams', 'slug')->ignore($testId),
            ],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'total_questions'  => ['nullable', 'integer', 'min:1'],
            'is_active'        => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Test name is required.',
            'slug.regex'               => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique'              => 'This slug is already taken by another test.',
            'duration_minutes.required' => 'Please enter test duration.',
            'duration_minutes.min'     => 'Duration must be at least 1 minute.',
            'duration_minutes.max'     => 'Duration cannot exceed 600 minutes (10 hours).',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
