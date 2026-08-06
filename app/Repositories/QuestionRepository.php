<?php

namespace App\Repositories;

use App\Models\Question;
use Illuminate\Support\Collection;

class QuestionRepository
{
    public function getBySetId(int $setId): Collection
    {
        return Question::where('question_set_id', $setId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getByQid(string $qid): ?Question
    {
        return Question::where('qid', $qid)->first();
    }

    public function getRandomBySetId(int $setId, int $count = 10): Collection
    {
        return Question::where('question_set_id', $setId)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }
}
