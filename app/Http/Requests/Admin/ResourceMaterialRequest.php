<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ResourceMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic_id'    => ['required', 'integer', 'exists:topics,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file_url'    => ['nullable', 'string', 'max:1000', 'url'],
            'file'        => ['required_without:file_url', 'nullable', 'file', 'mimes:pdf,csv,doc,docx,ppt,pptx,xls,xlsx', 'max:51200'],
            'file_type'   => ['nullable', 'string', 'in:pdf,csv,docx,ppt,xlsx,other'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort_order' => $this->sort_order ?? 0,
            'is_active'  => $this->boolean('is_active'),
            'file_type'  => $this->detectFileType(),
        ]);
    }

    /** Auto-detect file_type from uploaded file or explicit input. */
    private function detectFileType(): ?string
    {
        if ($this->filled('file_type')) {
            return $this->file_type;
        }
        if ($this->hasFile('file')) {
            $ext = strtolower($this->file('file')->getClientOriginalExtension());
            // Map common extensions
            $map = [
                'pdf'  => 'pdf',
                'csv'  => 'csv',
                'doc'  => 'docx',
                'docx' => 'docx',
                'ppt'  => 'ppt',
                'pptx' => 'ppt',
                'xls'  => 'xlsx',
                'xlsx' => 'xlsx',
            ];
            return $map[$ext] ?? 'other';
        }
        return null;
    }
}