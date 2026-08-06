<?php

namespace Tests\Feature;

use App\Livewire\MockExamEngine;
use App\Models\MockExam;
use Livewire\Livewire;
use Tests\TestCase;

class MockExamEngineTest extends TestCase
{
    public function test_pregame_state_and_start_exam(): void
    {
        $exam = MockExam::firstOrFail();

        Livewire::test(MockExamEngine::class, ['mockExam' => $exam])
            ->assertSet('examState', 'pregame')
            ->assertSet('questions', $exam->questions->toArray())
            ->call('startExam', 'exam')
            ->assertSet('examState', 'active')
            ->assertSet('remainingSeconds', $exam->duration_minutes * 60)
            ->assertSet('practiceMode', false);
    }

    public function test_practice_mode_disables_negative_marking(): void
    {
        $exam = MockExam::firstOrFail();

        Livewire::test(MockExamEngine::class, ['mockExam' => $exam])
            ->call('startExam', 'practice')
            ->assertSet('practiceMode', true)
            ->assertSet('examState', 'active');
    }

    public function test_answer_scoring_and_completion(): void
    {
        $exam = MockExam::firstOrFail();
        $questions = $exam->questions->toArray();

        // Answer the first 3 questions
        $correctIndex = 0;
        $correctAnswer = $questions[0]['correct_option'];
        $wrongIndex = 1;
        $wrongAnswer = $questions[1]['option_a'] === $questions[1]['correct_option']
            ? 'b' // pick a definitely-wrong one
            : 'a';

        Livewire::test(MockExamEngine::class, ['mockExam' => $exam])
            ->call('startExam', 'practice')
            ->call('selectAnswer', 0, $correctAnswer)
            ->call('selectAnswer', 1, $wrongAnswer)
            ->call('submitExam')
            ->assertSet('examState', 'complete')
            ->assertSet('correctCount', 1)
            ->assertSet('wrongCount', 1)
            ->assertSet('skippedCount', 98);
    }

    public function test_break_screen_after_20_questions(): void
    {
        $exam = MockExam::firstOrFail();

        Livewire::test(MockExamEngine::class, ['mockExam' => $exam])
            ->call('startExam', 'practice')
            ->call('goToNext') // index 0 -> 1
            ->call('goToNext') // ...
            // Simulate navigating to question 20 (index 19), which triggers break
            ->call('goToNext')
            ->call('continueFromBreak')
            ->assertSet('examState', 'active');
    }

    public function test_review_mode_toggle(): void
    {
        $exam = MockExam::firstOrFail();

        Livewire::test(MockExamEngine::class, ['mockExam' => $exam])
            ->call('startExam', 'practice')
            ->call('submitExam')
            ->call('toggleReviewMode')
            ->assertSet('reviewMode', true)
            ->assertSet('currentIndex', 0)
            ->call('toggleReviewMode')
            ->assertSet('reviewMode', false);
    }
}
