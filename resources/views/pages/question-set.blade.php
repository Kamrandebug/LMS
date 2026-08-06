<x-layouts.app>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 pt-8">
        <!-- Breadcrumb -->
        <div class="mb-6 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-brand-primary transition-colors">Home</a>
            <span class="text-gray-400 mx-2">›</span>
            <a href="{{ route('subjects.show', $subject) }}" class="text-gray-500 hover:text-brand-primary transition-colors">{{ $subject->name }}</a>
            <span class="text-gray-400 mx-2">›</span>
            <span class="text-gray-400">{{ $topic->name }}</span>
            <span class="text-gray-400 mx-2">›</span>
            <span class="text-brand-primary font-bold">Set {{ $set->set_number }}</span>
        </div>

        {{-- Progress Bar --}}
        <div class="mb-8 select-none">
            <div class="flex justify-between text-sm font-bold text-gray-500 mb-2">
                <span>Question <span id="q-current">1</span>/<span id="q-total">{{ count($set->questions) }}</span></span>
                <span id="streak-counter" class="text-orange-500 hidden">🔥 0 streak</span>
            </div>
            <div class="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                <div id="progress-bar" class="h-full bg-brand-primary transition-all duration-500 ease-out" style="width: 0%"></div>
            </div>
        </div>

        {{-- Quiz Container --}}
        <div id="quiz-container">
            @foreach($set->questions as $index => $question)
                <div class="quiz-card {{ $index === 0 ? 'block' : 'hidden' }} slide-in"
                     id="card-{{ $index }}"
                     data-correct="{{ $question->correct_option_index }}"
                     data-qid="{{ $question->qid }}">

                    {{-- Flag Button --}}
                    <div class="flex justify-end mb-2">
                        <button onclick="openFlagModal({{ $index }})"
                                class="text-gray-300 hover:text-red-500 transition-colors p-2" title="Report Issue">
                            🏳️ Report
                        </button>
                    </div>

                    <h2 class="text-xl md:text-2xl font-heading font-bold text-gray-800 dark:text-gray-100 mb-6 leading-tight">
                        {{ $question->question }}
                    </h2>

                    <div class="grid gap-3">
                        @foreach(['a', 'b', 'c', 'd'] as $letter)
                            @php
                                $optionKey = 'option_' . $letter;
                                $optionText = $question->$optionKey;
                                $optionIndex = ord($letter) - 97; // a=0, b=1, c=2, d=3
                            @endphp
                            <button onclick="checkAnswer(this, {{ $index }}, {{ $optionIndex }})"
                                    class="option-btn"
                                    data-option="{{ $letter }}">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold flex items-center justify-center mr-3 group-hover:bg-brand-primary group-hover:text-white transition-colors text-sm shrink-0">
                                    {{ strtoupper($letter) }}
                                </span>
                                {{ $optionText }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Feedback Section --}}
                    <div id="feedback-{{ $index }}"
                         class="hidden mt-6 p-5 rounded-xl bg-blue-50 border border-blue-100 animate-fade-in-up">
                        <div class="font-bold text-blue-800 mb-2 flex items-center gap-2">
                            💡 Explanation
                        </div>
                        <p class="text-blue-700 leading-relaxed">
                            {{ $question->explanation }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Result Screen --}}
        <div id="result-screen" class="hidden text-center py-12">
            <div id="result-content" class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm inline-block w-full max-w-md mx-auto mb-8 border border-gray-100 dark:border-gray-800">
                <div class="text-6xl mb-6">🎉</div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Quiz Completed!</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-2">You scored <span class="font-bold text-brand-primary text-xl" id="final-score">0</span></p>
                <p class="text-gray-500 text-sm mb-6">Can you beat me?</p>
            </div>

            <button onclick="shareResult()"
                    class="w-full max-w-md mx-auto bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl mb-4 transition-all flex items-center justify-center gap-2 shadow-lg shadow-green-500/20 active:scale-95">
                <span class="text-xl">📱</span> Challenge a Friend (WhatsApp)
            </button>

            <x-ad-slot position="result_page" />

            <div id="redemption-prompt" class="hidden max-w-sm mx-auto bg-red-50 dark:bg-red-900/20 p-6 rounded-2xl mb-8 border border-red-100 dark:border-red-800">
                <h3 class="font-bold text-red-800 dark:text-red-300 mb-2">Wait! Not so fast.</h3>
                <p class="text-sm text-red-600 dark:text-red-200 mb-4">You missed <span id="missed-count">0</span> questions. Let's fix them.</p>
                <button onclick="startRedemption()" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition shadow-lg shadow-red-500/30 active:scale-95">
                    🛡️ Start Redemption Round
                </button>
            </div>

            <a href="{{ route('subjects.show', $subject) }}"
               class="inline-block bg-brand-primary text-white font-bold py-3 px-8 rounded-full hover:shadow-lg hover:-translate-y-1 transition-all">
                Back to Topics
            </a>
        </div>

        {{-- Break Screen --}}
        <div id="break-screen" class="hidden text-center py-12">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-xl inline-block w-full max-w-md mx-auto mb-8 border border-gray-100 dark:border-gray-800 animate-pop">
                <div class="text-6xl mb-6">🥤</div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Take a break, champ!</h2>
                <p class="text-gray-500 dark:text-gray-400 italic mb-8" id="break-quote">
                    "Start where you are. Use what you have. Do what you can."
                </p>
                <x-ad-slot position="between_questions" />
                <button onclick="continueFromBreak()" class="w-full bg-brand-primary text-white font-bold py-4 rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all active:scale-95">
                    Continue Studying →
                </button>
            </div>
        </div>
    </div>

    {{-- Sticky Action Bar --}}
    <div id="action-bar" class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-gray-800 p-4 transform translate-y-full transition-transform duration-300 z-40">
        <div class="max-w-3xl mx-auto flex gap-3">
            <button onclick="prevQuestion()" id="prev-btn" disabled
                    class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-4 rounded-xl text-lg hover:bg-gray-200 dark:hover:bg-gray-600 active:scale-95 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                ← Previous
            </button>
            <button onclick="finishQuiz()"
                    class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-4 rounded-xl text-lg hover:bg-gray-200 dark:hover:bg-gray-600 active:scale-95 transition-all">
                🏁 Finish
            </button>
            <button onclick="nextQuestion()" id="next-btn"
                    class="flex-[2] bg-green-500 text-white font-bold py-4 rounded-xl text-lg shadow-lg shadow-green-500/30 hover:bg-green-600 active:scale-95 transition-all">
                Continue
            </button>
        </div>
    </div>

    {{-- Flag Modal --}}
    <div id="flag-modal" class="fixed inset-0 bg-black/50 z-[60] hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full shadow-2xl animate-fade-in-up">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2 dark:text-white">
                🏳️ Report Issue
                <button onclick="closeFlagModal()" class="ml-auto text-gray-400 hover:text-gray-600">✕</button>
            </h3>
            <form onsubmit="submitFlag(event)">
                <input type="hidden" id="flag-qid">
                <div class="space-y-3 mb-4">
                    <label class="flex items-center gap-3 p-3 border dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700">
                        <input type="radio" name="reason" value="Wrong Answer" class="text-red-500 focus:ring-red-500" checked>
                        <span class="text-sm font-medium dark:text-gray-300">Wrong Answer Key</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700">
                        <input type="radio" name="reason" value="Typo" class="text-red-500 focus:ring-red-500">
                        <span class="text-sm font-medium dark:text-gray-300">Typo / Grammar</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700">
                        <input type="radio" name="reason" value="Wrong Set" class="text-red-500 focus:ring-red-500">
                        <span class="text-sm font-medium dark:text-gray-300">MCQ not in right set</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700">
                        <input type="radio" name="reason" value="Confusing Explanation" class="text-red-500 focus:ring-red-500">
                        <span class="text-sm font-medium dark:text-gray-300">Explanation Confusing</span>
                    </label>
                </div>
                <button type="submit" class="w-full bg-gray-900 dark:bg-black text-white font-bold py-3 rounded-xl hover:bg-black">Submit Report</button>
            </form>
        </div>
    </div>

    {{-- Canvas for confetti --}}
    <canvas id="canvas" class="fixed inset-0 pointer-events-none z-50"></canvas>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        // State
        let currentIdx = 0;
        let score = 0;
        const SET_ID = '{{ $subject->slug }}-{{ $topic->slug }}-set-{{ $set->set_number }}';
        const TOTAL_QUESTIONS = {{ count($set->questions) }};
        let wrongIndexes = [];
        let isRedemption = false;
        const SESSION_KEY = 'quiz_v2_{{ $set->id }}';

        const questions = @json($set->questions);

        // Check resume
        document.addEventListener('DOMContentLoaded', function() {
            const saved = localStorage.getItem(SESSION_KEY);
            if (saved && !isRedemption) {
                document.getElementById('resume-modal')?.classList.remove('hidden');
            } else {
                startQuiz();
            }
        });

        function startQuiz() {
            updateProgress();
            updateNavButtons();
        }

        function updateProgress() {
            document.getElementById('q-current').textContent = currentIdx + 1;
            const pct = ((currentIdx + 1) / TOTAL_QUESTIONS) * 100;
            document.getElementById('progress-bar').style.width = Math.min(pct, 100) + '%';
        }

        function checkAnswer(btn, qIdx, selectedIdx) {
            const card = document.getElementById('card-' + qIdx);
            const correctIdx = parseInt(card.dataset.correct);
            const btns = card.querySelectorAll('.option-btn');
            btns.forEach(b => b.disabled = true);
            btns.forEach(b => b.classList.add('cursor-not-allowed', 'opacity-70'));

            if (selectedIdx === correctIdx) {
                btn.classList.remove('border-gray-200', 'hover:border-brand-primary');
                btn.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20', 'text-green-700', 'dark:text-green-300');
                score++;
                window.trackEvent?.('quiz_correct', { set_id: SET_ID });
            } else {
                btn.classList.remove('border-gray-200', 'hover:border-brand-primary');
                btn.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-300');
                btns[correctIdx].classList.remove('border-gray-200', 'hover:border-brand-primary');
                btns[correctIdx].classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20', 'text-green-700', 'dark:text-green-300');
                wrongIndexes.push(qIdx);

                // Trigger wiggle animation
                card.classList.add('animate-wiggle');
                setTimeout(() => card.classList.remove('animate-wiggle'), 500);
                window.trackEvent?.('quiz_wrong', { set_id: SET_ID });
            }

            document.getElementById('feedback-' + qIdx).classList.remove('hidden');
            saveProgress();
            document.getElementById('action-bar').classList.remove('translate-y-full');
            document.getElementById('action-bar').classList.add('translate-y-0');
        }

        function nextQuestion() {
            const nextIdx = currentIdx + 1;
            if (nextIdx >= TOTAL_QUESTIONS) { finishQuiz(); return; }

            document.getElementById('card-' + currentIdx).classList.remove('block');
            document.getElementById('card-' + currentIdx).classList.add('hidden');
            document.getElementById('card-' + nextIdx).classList.remove('hidden');
            document.getElementById('card-' + nextIdx).classList.add('block', 'slide-in');
            currentIdx = nextIdx;
            updateProgress();

            // Break screen
            if ((currentIdx + 1) % 10 === 0 && currentIdx > 0 && !isRedemption) {
                document.getElementById('quiz-container').classList.add('hidden');
                document.getElementById('break-screen').classList.remove('hidden');
            }

            document.getElementById('action-bar').classList.remove('translate-y-0');
            document.getElementById('action-bar').classList.add('translate-y-full');
            updateNavButtons();
        }

        function prevQuestion() {
            const prevIdx = currentIdx - 1;
            if (prevIdx < 0) return;

            document.getElementById('card-' + currentIdx).classList.remove('block');
            document.getElementById('card-' + currentIdx).classList.add('hidden');
            document.getElementById('card-' + prevIdx).classList.remove('hidden');
            document.getElementById('card-' + prevIdx).classList.add('block', 'slide-in');
            currentIdx = prevIdx;
            updateProgress();
            updateNavButtons();
        }

        function updateNavButtons() {
            document.getElementById('prev-btn').disabled = currentIdx === 0;
            const nextBtn = document.getElementById('next-btn');
            nextBtn.textContent = (currentIdx === TOTAL_QUESTIONS - 1) ? '🏁 Finish' : 'Continue';
        }

        function continueFromBreak() {
            document.getElementById('break-screen').classList.add('hidden');
            document.getElementById('quiz-container').classList.remove('hidden');
        }

        function finishQuiz() {
            document.getElementById('quiz-container').classList.add('hidden');
            document.getElementById('action-bar').classList.add('translate-y-full');
            document.getElementById('result-screen').classList.remove('hidden');
            document.getElementById('final-score').textContent = score + '/' + TOTAL_QUESTIONS;
            localStorage.removeItem(SESSION_KEY);

            // Mark as completed
            const completed = JSON.parse(localStorage.getItem('completed_sets') || '[]');
            if (!completed.includes(SET_ID)) {
                completed.push(SET_ID);
                localStorage.setItem('completed_sets', JSON.stringify(completed));
            }

            if (score < TOTAL_QUESTIONS) {
                document.getElementById('missed-count').textContent = TOTAL_QUESTIONS - score;
                document.getElementById('redemption-prompt').classList.remove('hidden');
            }

            const percentage = Math.round((score / TOTAL_QUESTIONS) * 100);

            // Confetti for perfect score
            if (percentage === 100) {
                if (typeof confetti === 'function') {
                    confetti({ particleCount: 150, spread: 80, origin: { y: 0.6 } });
                    setTimeout(() => confetti({ particleCount: 100, angle: 60, spread: 55, origin: { x: 0 } }), 250);
                    setTimeout(() => confetti({ particleCount: 100, angle: 120, spread: 55, origin: { x: 1 } }), 400);
                }
            }

            window.trackEvent?.('quiz_complete', { set_id: SET_ID, score, total: TOTAL_QUESTIONS, percentage });
        }

        function saveProgress() {
            if (isRedemption) return;
            localStorage.setItem(SESSION_KEY, JSON.stringify({
                idx: currentIdx,
                score: score,
                wrong: wrongIndexes,
                total: TOTAL_QUESTIONS
            }));
        }

        function startRedemption() {
            const ids = wrongIndexes.join(',');
            window.location.href = window.location.pathname + '?redemption_ids=' + ids;
        }

        function shareResult() {
            const text = '🎯 I scored ' + score + '/' + TOTAL_QUESTIONS + ' on LearnUp! Can you beat me?';
            const url = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(text + ' ' + window.location.href);
            window.open(url, '_blank');
        }

        // Flag Modal functions
        function openFlagModal(idx) {
            document.getElementById('flag-qid').value = idx;
            document.getElementById('flag-modal').classList.remove('hidden');
        }
        function closeFlagModal() { document.getElementById('flag-modal').classList.add('hidden'); }
        function submitFlag(e) {
            e.preventDefault();
            const idx = document.getElementById('flag-qid').value;
            const reason = document.querySelector('input[name="reason"]:checked').value;
            const question = questions[idx];

            fetch('/api/v1/report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: question?.id,
                    question_set_id: {{ $set->id }},
                    report_type: reason
                })
            });

            closeFlagModal();
            alert('Thanks! Your report has been submitted.');
        }
    </script>
    @endpush

    <style>
        .slide-in { animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    </style>
</x-layouts.app>
