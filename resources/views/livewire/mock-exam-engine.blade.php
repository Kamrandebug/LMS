<div>
    {{-- Pre-Start --}}
    @if($examState === 'pregame')
        <div class="flex items-center justify-center min-h-[60vh]">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-xl p-8 max-w-lg w-full text-center">
                <div class="text-6xl mb-4">📝</div>
                <h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-3">
                    {{ preg_match('/\d+$/', $mockExam->name, $matches) ? 'Mock Test ' . $matches[0] : $mockExam->name }}
                </h2>
                <p class="text-gray-500 dark:text-gray-400 text-lg mb-6">
                    Timed exam with {{ $mockExam->total_questions }} questions.
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
                        <div class="text-xl font-bold text-gray-800 dark:text-white">⏱ {{ $mockExam->duration_minutes }} min</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Duration</div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
                        <div class="text-xl font-bold text-gray-800 dark:text-white">📊 {{ $mockExam->total_questions }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Questions</div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
                        <div class="text-xl font-bold text-amber-600 dark:text-amber-400">
                            📝 @if($mockExam->has_negative_marking) −{{ $mockExam->negative_marking_value }} @else 0 @endif
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Negative</div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
                        <div class="text-xl font-bold text-brand-primary">👥 {{ $mockExam->attempt_count }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Attempts</div>
                    </div>
                </div>

                <div class="grid gap-3">
                    <button wire:click="startExam('exam')"
                            class="w-full bg-brand-primary text-white font-bold py-4 px-6 rounded-xl text-lg shadow-lg hover:shadow-xl active:scale-95 transition-all">
                        📝 Start Exam (Negative Marking)
                    </button>
                    <button wire:click="startExam('practice')"
                            class="w-full bg-white dark:bg-slate-800 text-brand-primary border-2 border-brand-primary font-bold py-4 px-6 rounded-xl text-lg hover:bg-brand-primary hover:text-white active:scale-95 transition-all">
                        📖 Start Practice (No Negative Marking)
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Active Exam --}}
    @if($examState === 'active')
        <div>
            {{-- Timer and progress --}}
            <div class="sticky top-16 z-40 bg-slate-50 dark:bg-slate-950 pb-4 pt-2">
                <div class="flex justify-between items-center mb-2">
                    <div class="text-lg font-mono font-bold {{ (!$practiceMode && $remainingSeconds < 300) ? 'text-red-500 animate-pulse' : 'text-gray-700 dark:text-gray-200' }}">
                        ⏱ {{ $this->formattedTime }}
                    </div>
                    <div class="text-sm font-bold text-gray-500">
                        Q{{ $currentIndex + 1 }}/{{ count($questions) }}
                    </div>
                </div>
                <div class="h-1.5 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-primary transition-all duration-500 ease-out rounded-full"
                         style="width: {{ $this->progress }}%"></div>
                </div>
            </div>

            {{-- Question --}}
            @if(isset($questions[$currentIndex]))
                @php $question = $questions[$currentIndex]; @endphp
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 md:p-8">
                    @if($reviewMode)
                        @php
                            $answer = $answers[$currentIndex] ?? null;
                            $isCorrect = $answer && $answer !== 'skipped' && $answer === $question['correct_option'];
                            $isWrong = $answer && $answer !== 'skipped' && $answer !== $question['correct_option'];
                        @endphp
                        <div class="text-center mb-4">
                            @if($isCorrect)
                                <span class="inline-block px-4 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-sm font-bold rounded-full">✅ Correct</span>
                            @elseif($isWrong)
                                <span class="inline-block px-4 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-sm font-bold rounded-full">❌ Incorrect</span>
                            @else
                                <span class="inline-block px-4 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm font-bold rounded-full">⚠️ Unattempted</span>
                            @endif
                        </div>
                    @endif

                    <h2 class="text-xl md:text-2xl font-heading font-bold text-gray-800 dark:text-gray-100 mb-6 leading-tight">
                        <span class="text-brand-primary font-mono text-sm mr-2">Q{{ $currentIndex + 1 }}.</span>
                        {{ $question['question_text'] }}
                    </h2>

                    <div class="grid gap-3">
                        @foreach(['a', 'b', 'c', 'd'] as $letter)
                            @php $optionKey = 'option_' . $letter; @endphp
                            <button wire:click="{{ $reviewMode ? '' : "selectAnswer($currentIndex, '$letter')" }}"
                                    @if($reviewMode || isset($answers[$currentIndex])) disabled @endif
                                    class="w-full text-left px-4 py-3 md:py-4 rounded-xl border-2 transition-all duration-200 flex items-center
                                    @if($reviewMode || isset($answers[$currentIndex]))
                                        @if($letter === $question['correct_option'])
                                            border-green-500 bg-green-50 dark:bg-green-900/20
                                        @elseif(isset($answers[$currentIndex]) && $answers[$currentIndex] === $letter)
                                            border-red-500 bg-red-50 dark:bg-red-900/20
                                        @else
                                            border-gray-200 dark:border-gray-700 bg-white dark:bg-slate-800 opacity-50
                                        @endif
                                    @else
                                        @if(isset($answers[$currentIndex]) && $answers[$currentIndex] === $letter)
                                            border-brand-primary bg-brand-primary/5
                                        @else
                                            border-gray-200 dark:border-gray-700 bg-white dark:bg-slate-800 hover:border-brand-primary hover:bg-brand-primary/5
                                        @endif
                                    @endif">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold flex items-center justify-center mr-3 text-sm shrink-0">
                                    {{ strtoupper($letter) }}
                                </span>
                                <span>{{ $question[$optionKey] }}</span>

                                @if($reviewMode || isset($answers[$currentIndex]))
                                    @if($letter === $question['correct_option'])
                                        <span class="ml-auto text-green-500 font-bold text-xl">✓</span>
                                    @elseif(isset($answers[$currentIndex]) && $answers[$currentIndex] === $letter)
                                        <span class="ml-auto text-red-500 font-bold text-xl">✗</span>
                                    @endif
                                @endif
                            </button>
                        @endforeach
                    </div>

                    @if(($reviewMode || isset($answers[$currentIndex])) && !empty($question['explanation']))
                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl text-sm text-gray-700 dark:text-gray-200">
                            <strong class="text-blue-600 dark:text-blue-400">Explanation:</strong> {{ $question['explanation'] }}
                        </div>
                    @endif

                    {{-- Navigation --}}
                    <div class="flex gap-3 mt-8">
                        <button wire:click="{{ $reviewMode ? 'toggleReviewMode' : 'goToPrevious' }}"
                                @if(!$reviewMode && $currentIndex === 0) disabled @endif
                                class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-30 transition-all">
                            @if($reviewMode) ← Exit Review @else ← Previous @endif
                        </button>
                        @if(!$reviewMode)
                            <button wire:click="skipQuestion"
                                    class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                {{ isset($answers[$currentIndex]) ? 'Next →' : '⏭ Skip' }}
                            </button>
                            <button wire:click="submitExam"
                                    class="flex-1 bg-brand-primary text-white font-bold py-3 rounded-xl hover:bg-green-600 transition-all">
                                🏁 Submit
                            </button>
                        @else
                            <button wire:click="goToNext"
                                    class="flex-1 bg-brand-primary text-white font-bold py-3 rounded-xl hover:bg-green-600 transition-all">
                                Next →
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Poll for timer --}}
            <div wire:poll.1000ms="tick"></div>
        </div>
    @endif

    {{-- Break Screen --}}
    @if($examState === 'break')
        <div class="flex items-center justify-center min-h-[60vh]">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-xl p-8 max-w-lg w-full text-center">
                <div class="text-6xl mb-4">☕</div>
                <h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-3">Take a Break!</h2>
                <p class="text-gray-500 dark:text-gray-400 text-lg mb-6">
                    You've completed {{ $currentIndex + 1 }} questions. Great progress! Take a moment, then continue.
                </p>
                <div class="text-xl font-bold text-brand-primary mb-8">Q{{ $currentIndex + 1 }}/{{ count($questions) }}</div>
                <button wire:click="continueFromBreak"
                        class="w-full bg-brand-primary text-white font-bold py-4 px-6 rounded-xl text-lg shadow-lg hover:shadow-xl active:scale-95 transition-all">
                    Continue →
                </button>
            </div>
        </div>
    @endif

    {{-- Results --}}
    @if($examState === 'complete')
        <div class="text-center py-12">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm inline-block w-full max-w-md mx-auto mb-8 border border-gray-100 dark:border-gray-800">
                <div class="text-6xl mb-6">{{ $this->resultEmoji }}</div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Exam Complete!</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                    Score: <span class="font-bold text-brand-primary text-xl">{{ $practiceMode ? $rawScore : number_format($score, 2) }}/{{ count($questions) }}</span>
                </p>
                <p class="text-sm text-gray-500 mb-2">{{ $this->percentage }}% · ⏱ {{ $this->elapsedTime }} elapsed</p>
                <div class="flex justify-center gap-6 mt-4 text-sm">
                    <span class="text-green-600 font-bold">✓ {{ $correctCount }} Correct</span>
                    <span class="text-red-600 font-bold">✗ {{ $wrongCount }} Wrong</span>
                    <span class="text-gray-400 font-bold">⏭ {{ $skippedCount }} Skipped</span>
                </div>
                @if(!$practiceMode && $mockExam->has_negative_marking)
                    <div class="mt-3 text-sm text-gray-500">
                        (Adjusted for negative marking)
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
                <a href="{{ $this->whatsAppShareUrl }}" target="_blank" rel="noopener"
                   class="inline-block bg-green-500 text-white font-bold py-3 px-8 rounded-full hover:shadow-lg hover:-translate-y-1 transition-all">
                    📱 Share on WhatsApp
                </a>
                <button wire:click="toggleReviewMode"
                        class="inline-block bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold py-3 px-8 rounded-full hover:shadow-lg hover:-translate-y-1 transition-all">
                    🔍 Review Answers
                </button>
            </div>

            <a href="{{ route('mock-exams.index') }}"
               class="inline-block bg-brand-primary text-white font-bold py-3 px-8 rounded-full hover:shadow-lg hover:-translate-y-1 transition-all">
                Back to Mock Tests
            </a>
        </div>
    @endif
</div>
