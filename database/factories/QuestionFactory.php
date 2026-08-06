<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionSet;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        $options = [
            'option_a' => $this->faker->sentence(4),
            'option_b' => $this->faker->sentence(4),
            'option_c' => $this->faker->sentence(4),
            'option_d' => $this->faker->sentence(4),
        ];
        $correct = $this->faker->randomElement(['a', 'b', 'c', 'd']);

        return [
            'question_set_id' => QuestionSet::factory(),
            'qid' => $this->faker->unique()->regexify('[a-z]+-[a-z]+-s[1-9]-q[1-9]'),
            'question' => $this->faker->sentence(10) . '?',
            ...$options,
            'correct_option' => $correct,
            'explanation' => $this->faker->paragraph(),
            'sort_order' => $this->faker->numberBetween(0, 19),
            'is_active' => true,
        ];
    }
}
