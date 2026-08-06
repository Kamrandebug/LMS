<div>
    {{-- Progress Bar --}}
    @if(!$quizComplete)
        <div class="mb-8 select-none">
            <div class="flex justify-between text-sm font-bold text-gray-500 mb-2">
                <span>Question <span id="q-current">{{ $currentIndex + 1 }}</span>/<span id="q-total">{{ count($questions) }}</span></span>
                @if($this->getStreakCount() > 0)
                    <span id="streak-counter" class="text-orange-500">🔥 {{ $this->getStreakCount() }} streak</span>
                @endif
            </div>
            <div class="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                <div id="progress-bar" class="h-full bg-brand-primary transition-all duration-500 ease-out rounded-full"
                     style="width: {{ $this->getProgressPercentage() }}%"></div>
            </div>
        </div>

        {{-- Question Card --}}
        @if($currentQuestion)
            <div class="quiz-card block slide-in current-question-card"
                 id="card-{{ $currentIndex }}"
                 data-correct="{{ $currentQuestion['correct_option'] }}">

                <div class="flex justify-end mb-2">
                    <button wire:click="$dispatch('open-flag-modal', { index: {{ $currentIndex }} })"
                            class="text-gray-300 hover:text-red-500 transition-colors p-2" title="Report Issue">
                        🏳️ Report
                    </button>
                </div>

                <h2 class="text-xl md:text-2xl font-heading font-bold text-gray-800 dark:text-gray-100 mb-6 leading-tight">
                    {{ $currentQuestion['question'] }}
                </h2>

                <div class="grid gap-3">
                    @foreach(['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $letter => $optionKey)
                        @php
                            $optionIndex = ord($letter) - 97;
                            $isSelected = $selectedAnswer === $letter;
                            $isCorrectOption = $currentQuestion['correct_option'] === $letter;
                            $btnClass = 'option-btn';
                            if ($answered) {
                                if ($isCorrectOption) {
                                    $btnClass = 'option-btn option-btn-correct';
                                } elseif ($isSelected && !$isCorrectOption) {
                                    $btnClass = 'option-btn option-btn-wrong';
                                } else {
                                    $btnClass = 'option-btn option-btn-disabled';
                                }
                            }
                        @endphp
                        <button wire:click="selectAnswer('{{ $letter }}')"
                                @if($answered) disabled @endif
                                class="{{ $btnClass }}">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold flex items-center justify-center mr-3 group-hover:bg-brand-primary group-hover:text-white transition-colors text-sm shrink-0">
                                {{ strtoupper($letter) }}
                            </span>
                            {{ $currentQuestion[$optionKey] }}
                            @if($answered && $isCorrectOption)
                                <span class="ml-auto text-green-500">✓</span>
                            @elseif($answered && $isSelected && !$isCorrectOption)
                                <span class="ml-auto text-red-500">✗</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- Feedback --}}
                @if($answered && $currentQuestion['explanation'])
                    <div class="mt-6 p-5 rounded-xl bg-blue-50 border border-blue-100 animate-fade-in-up">
                        <div class="font-bold text-blue-800 mb-2 flex items-center gap-2">
                            💡 Explanation
                        </div>
                        <p class="text-blue-700 leading-relaxed">
                            {{ $currentQuestion['explanation'] }}
                        </p>
                    </div>
                @endif
            </div>
        @endif
    @endif

    {{-- Result Screen --}}
    @if($quizComplete)
        <div class="text-center py-12">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm inline-block w-full max-w-md mx-auto mb-8 border border-gray-100 dark:border-gray-800">
                <div class="text-6xl mb-6">🎉</div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Quiz Completed!</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                    You scored <span class="font-bold text-brand-primary text-xl">{{ $score }}/{{ count($questions) }}</span>
                </p>
                <p class="text-gray-500 text-sm mb-6">Can you beat me?</p>
            </div>

            <button wire:click="shareResult"
                    class="w-full max-w-md mx-auto bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl mb-4 transition-all flex items-center justify-center gap-2 shadow-lg shadow-green-500/20 active:scale-95">
                <span class="text-xl">📱</span> Challenge a Friend (WhatsApp)
            </button>

            @if(count($wrongIndexes) > 0)
                <div class="max-w-sm mx-auto bg-red-50 dark:bg-red-900/20 p-6 rounded-2xl mb-8 border border-red-100 dark:border-red-800">
                    <h3 class="font-bold text-red-800 dark:text-red-300 mb-2">Wait! Not so fast.</h3>
                    <p class="text-sm text-red-600 dark:text-red-200 mb-4">You missed <span>{{ count($wrongIndexes) }}</span> questions. Let's fix them.</p>
                    <button wire:click="startRedemption"
                            class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition shadow-lg shadow-red-500/30 active:scale-95">
                        🛡️ Start Redemption Round
                    </button>
                </div>
            @endif

            <a href="{{ route('subjects.show', $questionSet->topic->subject) }}"
               class="inline-block bg-brand-primary text-white font-bold py-3 px-8 rounded-full hover:shadow-lg hover:-translate-y-1 transition-all">
                Back to Topics
            </a>
        </div>
    @endif
</div>
