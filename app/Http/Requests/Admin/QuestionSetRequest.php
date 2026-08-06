<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionSetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        $setId    = $this->route('question_set')?->id;
        $topicId  = $this->input('topic_id');

        return [
            'topic_id'    => ['required', 'integer', 'exists:topics,id'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                // Slug unique within the same topic only
                Rule::unique('question_sets', 'slug')
                    ->where('topic_id', $topicId)
                    ->ignore($setId),
            ],
            'set_number'  => [
                'required', 
                'integer', 
                'min:1',
                Rule::unique('question_sets', 'set_number')
                    ->where('topic_id', $topicId)
                    ->ignore($setId),
            ],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'topic_id.required' => 'Please select a topic.',
            'topic_id.exists'   => 'Selected topic does not exist.',
            'slug.regex'        => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique'       => 'This slug is already used by another question set in the same topic.',
            'set_number.unique' => 'This set number is already used within the selected topic.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'  => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order') !== '' ? (int) $this->input('sort_order') : null,
            'set_number' => $this->input('set_number') !== '' ? (int) $this->input('set_number') : null,
        ]);
    }
}
