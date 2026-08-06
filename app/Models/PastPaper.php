<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastPaper extends Model
{
    protected $fillable = [
        'title', 'slug', 'exam_body', 'year',
        'file_path', 'external_url', 'is_active'
    ];
    protected $casts = ['is_active' => 'boolean'];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }
}
