<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id;

        return [
            'name'         => ['required', 'string', 'max:255'],
            'slug'         => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('subjects', 'slug')->ignore($subjectId),
            ],
            'description'  => ['nullable', 'string', 'max:1000'],
            'icon_svg'     => ['required', 'string', 'max:500'],
            'color_class'  => ['required', 'string', 'max:255'],
            'sort_order'   => ['required', 'integer', 'min:0'],
            'is_active'    => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex'  => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique' => 'This slug is already taken by another subject.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
