<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockExamQuestion extends Model
{
    protected $fillable = [
        'mock_exam_id', 'question_set_id',
        'question_text', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'explanation', 'sort_order'
    ];

    public function mockExam(): BelongsTo
    {
        return $this->belongsTo(MockExam::class);
    }

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class);
    }
}
