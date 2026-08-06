<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        $topicId   = $this->route('topic')?->id;
        $subjectId = $this->input('subject_id');

        return [
            'subject_id'  => ['required', 'integer', 'exists:subjects,id'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                // Slug must be unique within the same subject only
                Rule::unique('topics', 'slug')
                    ->where('subject_id', $subjectId)
                    ->ignore($topicId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.required' => 'Please select a subject.',
            'subject_id.exists'   => 'Selected subject does not exist.',
            'slug.regex'          => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique'         => 'This slug is already used by another topic in the same subject.',
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
