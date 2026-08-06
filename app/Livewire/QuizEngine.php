<?php

namespace App\Livewire;

use App\Models\QuestionSet;
use App\Services\QuizProgressService;
use App\Services\StreakService;
use App\Services\WhatsAppShareService;
use Livewire\Component;

class QuizEngine extends Component
{
    public QuestionSet $questionSet;
    public array $questions = [];
    public int $currentIndex = 0;
    public ?string $selectedAnswer = null;
    public bool $answered = false;
    public bool $isCorrect = false;
    public array $wrongIndexes = [];
    public array $answers = [];
    public bool $quizComplete = false;
    public bool $redemptionMode = false;
    public array $redemptionQueue = [];
    public int $score = 0;
    public bool $showResumeModal = false;
    public string $sessionKey = '';
    public string $quizState = 'loading'; // loading, active, break, complete

    protected $listeners = ['restartQuiz', 'startRedemption'];

    public function mount(QuestionSet $questionSet, bool $redemption = false, array $redemptionIds = []): void
    {
        $this->questionSet = $questionSet;
        $this->sessionKey = 'quiz_v2_' . $questionSet->id;
        $this->questions = $questionSet->questions->toArray();

        if ($redemption && !empty($redemptionIds)) {
            $this->redemptionMode = true;
            $this->redemptionQueue = $redemptionIds;
            $this->questions = collect($this->questions)
                ->whereIn('sort_order', $redemptionIds)
                ->values()
                ->toArray();
        }

        $this->loadProgress();
        $this->quizState = 'active';
    }

    public function loadProgress(): void
    {
        $service = app(QuizProgressService::class);
        $progress = $service->loadProgress($this->sessionKey);

        if ($progress && !$this->redemptionMode) {
            $this->showResumeModal = true;
            $this->currentIndex = $progress['idx'] ?? 0;
            $this->score = $progress['score'] ?? 0;
            $this->wrongIndexes = $progress['wrong'] ?? [];
            $this->answers = $progress['answers'] ?? [];
        }
    }

    public function resumeQuiz(): void
    {
        $this->showResumeModal = false;
        $this->quizState = 'active';
    }

    public function startOver(): void
    {
        $this->showResumeModal = false;
        $this->clearProgress();
        $this->currentIndex = 0;
        $this->score = 0;
        $this->wrongIndexes = [];
        $this->answers = [];
        $this->quizState = 'active';
    }

    public function saveProgress(): void
    {
        if ($this->redemptionMode) return;

        $service = app(QuizProgressService::class);
        $service->saveProgress($this->sessionKey, [
            'idx' => $this->currentIndex,
            'score' => $this->score,
            'wrong' => $this->wrongIndexes,
            'answers' => $this->answers,
            'total' => count($this->questions),
        ]);
    }

    public function clearProgress(): void
    {
        $service = app(QuizProgressService::class);
        $service->clearProgress($this->sessionKey);
    }

    public function selectAnswer(string $option): void
    {
        if ($this->answered) return;

        $this->selectedAnswer = $option;
        $this->answered = true;

        $correctOption = $this->questions[$this->currentIndex]['correct_option'];
        $this->isCorrect = $option === $correctOption;

        $this->answers[$this->currentIndex] = $option;

        if ($this->isCorrect) {
            $this->score++;
            $this->dispatch('answer-correct');
        } else {
            $this->wrongIndexes[] = $this->currentIndex;
            $this->dispatch('answer-wrong');
        }

        $this->saveProgress();

        // Check for break screen
        if (($this->currentIndex + 1) % config('learnup.quiz.break_after_questions', 10) === 0
            && $this->currentIndex > 0
            && !$this->redemptionMode) {
            $this->dispatch('show-break-screen');
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentIndex < count($this->questions) - 1) {
            $this->currentIndex++;
            $this->selectedAnswer = null;
            $this->answered = false;
            $this->isCorrect = false;
        } else {
            $this->finishQuiz();
        }
    }

    public function prevQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function finishQuiz(): void
    {
        $this->quizComplete = true;
        $this->quizState = 'complete';
        $this->clearProgress();

        $total = count($this->questions);
        $percentage = $total > 0 ? round(($this->score / $total) * 100) : 0;

        $this->dispatch('quiz-complete', score: $this->score, total: $total, percentage: $percentage);

        // Update streak
        $streakService = app(StreakService::class);
        $streakService->incrementStreak();
    }

    public function startRedemption(): void
    {
        if (empty($this->wrongIndexes)) return;

        $ids = implode(',', $this->wrongIndexes);
        $this->redirect(request()->url() . '?redemption_ids=' . $ids);
    }

    public function restartQuiz(): void
    {
        $this->currentIndex = 0;
        $this->score = 0;
        $this->wrongIndexes = [];
        $this->answers = [];
        $this->quizComplete = false;
        $this->quizState = 'active';
        $this->selectedAnswer = null;
        $this->answered = false;
        $this->clearProgress();
    }

    public function shareResult(): void
    {
        $service = app(WhatsAppShareService::class);
        $message = $service->buildQuizShareMessage(
            $this->questionSet->name,
            $this->score,
            count($this->questions)
        );
        $this->dispatch('share-whatsapp', message: $message);
    }

    public function getStreakCount(): int
    {
        return app(StreakService::class)->getStreak();
    }

    public function getProgressPercentage(): int
    {
        $total = count($this->questions);
        return $total > 0 ? (int)((($this->currentIndex + 1) / $total) * 100) : 0;
    }

    public function getCurrentQuestionProperty(): ?array
    {
        return $this->questions[$this->currentIndex] ?? null;
    }

    public function render()
    {
        return view('livewire.quiz-engine');
    }
}
