<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RecordedLectureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic_id'         => ['required', 'integer', 'exists:topics,id'],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'video_url'        => ['required', 'string', 'max:1000', 'url'],
            'platform'         => ['required', 'string', 'in:youtube,vimeo,other'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:999'],
            'sort_order'       => ['integer', 'min:0'],
            'is_active'        => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort_order' => $this->sort_order ?? 0,
            'is_active'  => $this->boolean('is_active'),
        ]);
    }
}
