<?php

namespace Database\Factories;

use App\Models\QuestionSet;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionSetFactory extends Factory
{
    protected $model = QuestionSet::class;

    public function definition(): array
    {
        return [
            'topic_id' => Topic::factory(),
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(),
            'set_number' => $this->faker->numberBetween(1, 10),
            'question_count' => 20,
            'is_active' => true,
        ];
    }
}
