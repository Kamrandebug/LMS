<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'completed_sets' => 'nullable|array',
            'completed_sets.*' => 'string|max:120',
            'streak' => 'nullable|integer|min:0|max:999',
            'current_set' => 'nullable|string|max:120',
            'current_question' => 'nullable|integer|min:0',
        ];
    }
}
