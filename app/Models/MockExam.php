<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MockExam extends Model
{
    protected $fillable = [
        'name', 'slug', 'exam_type', 'total_questions',
        'duration_minutes', 'negative_marking_value',
        'has_negative_marking', 'attempt_count', 'is_active'
    ];
    protected $casts = [
        'has_negative_marking' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected function casts(): array
    {
        return [
            'total_questions' => 'integer',
            'duration_minutes' => 'integer',
            'attempt_count' => 'integer',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(MockExamQuestion::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
