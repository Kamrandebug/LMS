<?php

namespace App\Livewire;

use App\Models\MockExam;
use App\Services\StreakService;
use App\Services\WhatsAppShareService;
use Livewire\Component;

class MockExamEngine extends Component
{
    public MockExam $mockExam;
    public array $questions = [];
    public int $currentIndex = 0;
    public array $answers = [];
    public bool $examComplete = false;
    public bool $practiceMode = false;
    public bool $reviewMode = false;
    public int $remainingSeconds;
    public float $score = 0;
    public int $rawScore = 0;
    public int $correctCount = 0;
    public int $wrongCount = 0;
    public int $skippedCount = 0;
    public int $percentage = 0;
    public int $elapsedSeconds = 0;
    public string $examState = 'pregame'; // pregame, active, break, complete

    public function mount(MockExam $mockExam, bool $practiceMode = false): void
    {
        $this->mockExam = $mockExam;
        $this->practiceMode = $practiceMode;
        $this->questions = $mockExam->questions->toArray();
        $this->remainingSeconds = $mockExam->duration_minutes * 60;
        $this->examState = 'pregame';
    }

    /**
     * Start the exam in the selected mode. Defaults to the component's
     * practiceMode flag unless the user explicitly picks a mode.
     */
    public function startExam(?string $mode = null): void
    {
        if ($mode === 'practice') {
            $this->practiceMode = true;
        } elseif ($mode === 'exam') {
            $this->practiceMode = false;
        }

        if (empty($this->questions)) {
            $this->dispatch('notify', type: 'error', message: 'No questions found for this exam yet.');
            return;
        }

        $this->examState = 'active';
        $this->currentIndex = 0;
        $this->answers = [];
        $this->remainingSeconds = $this->mockExam->duration_minutes * 60;
        $this->elapsedSeconds = 0;

        $this->mockExam->increment('attempt_count');
        $this->mockExam->refresh();
    }

    public function selectAnswer(int $questionIndex, string $option): void
    {
        if (isset($this->answers[$questionIndex])) return;
        $this->answers[$questionIndex] = $option;
    }

    public function skipQuestion(): void
    {
        if (!isset($this->answers[$this->currentIndex])) {
            $this->answers[$this->currentIndex] = 'skipped';
        }
        $this->goToNext();
    }

    public function goToNext(): void
    {
        // Break screen every 20 questions (but not on the very last question)
        if (($this->currentIndex + 1) < count($this->questions)
            && ($this->currentIndex + 1) % 20 === 0) {
            $this->examState = 'break';
            return;
        }

        if ($this->currentIndex < count($this->questions) - 1) {
            $this->currentIndex++;
        }
    }

    public function continueFromBreak(): void
    {
        $this->examState = 'active';
    }

    public function goToPrevious(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    /**
     * Called by wire:poll.1000ms — decrements the countdown timer each second.
     */
    public function tick(): void
    {
        if ($this->examState !== 'active') return;

        $this->remainingSeconds--;
        $this->elapsedSeconds++;

        // Exam mode auto-submits when time expires; practice mode keeps counting (overtime)
        if ($this->remainingSeconds <= 0 && !$this->practiceMode) {
            $this->submitExam();
        }
    }

    public function submitExam(): void
    {
        if ($this->examState === 'complete') return;

        $this->calculateScore();
        $this->examComplete = true;
        $this->examState = 'complete';

        $this->dispatch('exam-complete', [
            'score' => $this->score,
            'correct' => $this->correctCount,
            'wrong' => $this->wrongCount,
        ]);
    }

    public function calculateScore(): void
    {
        $this->correctCount = 0;
        $this->wrongCount = 0;
        $this->skippedCount = 0;

        foreach ($this->questions as $index => $question) {
            $answer = $this->answers[$index] ?? null;

            if (!$answer || $answer === 'skipped') {
                $this->skippedCount++;
                continue;
            }

            if ($answer === $question['correct_option']) {
                $this->correctCount++;
            } else {
                $this->wrongCount++;
            }
        }

        $total = count($this->questions);
        $this->rawScore = $this->correctCount;
        $this->percentage = $total > 0 ? (int) round(($this->correctCount / $total) * 100) : 0;

        if ($this->practiceMode) {
            $this->score = $this->correctCount;
        } else {
            $negativeValue = (float) $this->mockExam->negative_marking_value;
            $rawScore = $this->correctCount - ($this->wrongCount * $negativeValue);
            $this->score = max(0, $rawScore);
        }

        // Update streak
        app(StreakService::class)->incrementStreak();
    }

    /**
     * Toggle review mode to inspect answers, correct/wrong highlights and
     * explanations after the exam has been submitted.
     */
    public function toggleReviewMode(): void
    {
        $this->reviewMode = !$this->reviewMode;
        $this->currentIndex = 0;
    }

    public function getFormattedTimeProperty(): string
    {
        $seconds = $this->remainingSeconds;
        if ($this->practiceMode && $seconds < 0) {
            $abs = abs($seconds);
            return '+' . sprintf('%02d:%02d', floor($abs / 60), $abs % 60);
        }
        return sprintf('%02d:%02d', floor(max($seconds, 0) / 60), max($seconds, 0) % 60);
    }

    public function getElapsedTimeProperty(): string
    {
        $s = $this->elapsedSeconds;
        return sprintf('%02d:%02d', floor($s / 60), $s % 60);
    }

    public function getProgressProperty(): int
    {
        $total = count($this->questions);
        return $total > 0 ? (int)((count($this->answers) / $total) * 100) : 0;
    }

    public function getResultEmojiProperty(): string
    {
        return match (true) {
            $this->percentage >= 100 => '🏆',
            $this->percentage >= 80 => '🎉',
            $this->percentage >= 50 => '👍',
            default => '💪',
        };
    }

    public function getWhatsAppShareUrlProperty(): string
    {
        $service = app(WhatsAppShareService::class);
        $scoreText = $this->practiceMode
            ? "{$this->rawScore}/{$this->rawScore} ({$this->percentage}%)"
            : "{$this->score}/" . count($this->questions) . " (adjusted, {$this->percentage}%)";
        
        $name = preg_match('/\d+$/', $this->mockExam->name, $matches) 
            ? 'Mock Test ' . $matches[0] 
            : $this->mockExam->name;
            
        $message = "🎯 I scored {$scoreText} on \"{$name}\" at LearnUp! Can you beat me?";
        return $service->buildShareUrl($message);
    }

    public function render()
    {
        return view('livewire.mock-exam-engine');
    }
}
