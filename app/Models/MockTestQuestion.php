<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockTestQuestion extends Model
{
    protected $table = 'mock_exam_questions'; // Keeping table name same

    protected $fillable = [
        'mock_exam_id', 'question_set_id',
        'question_text', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'explanation', 'sort_order'
    ];

    public function mockTest(): BelongsTo
    {
        return $this->belongsTo(MockTest::class, 'mock_exam_id');
    }

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class);
    }
}
