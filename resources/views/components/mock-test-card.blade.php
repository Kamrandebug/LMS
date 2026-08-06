<a href="{{ route('mock-exams.show', $mockExam) }}"
   class="block bg-white dark:bg-slate-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 hover:border-brand-primary dark:hover:border-brand-primary hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
    <div class="flex items-start gap-4 mb-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
            📝
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="font-heading font-bold text-lg text-gray-900 dark:text-white group-hover:text-brand-primary transition-colors">
                {{ preg_match('/\d+$/', $mockExam->name, $matches) ? 'Mock Test ' . $matches[0] : $mockExam->name }}
            </h3>
            @if($mockExam->description)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $mockExam->description }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-3 gap-3 text-center text-sm">
        <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-2.5">
            <div class="font-bold text-gray-800 dark:text-white text-xs">⏱</div>
            <div class="text-xs text-gray-500">{{ $mockExam->duration_minutes }} min</div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-2.5">
            <div class="font-bold text-gray-800 dark:text-white text-xs">📊</div>
            <div class="text-xs text-gray-500">{{ $mockExam->total_questions }} Qs</div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-2.5">
            <div class="font-bold text-amber-600 dark:text-amber-400 text-xs">📝</div>
            <div class="text-xs text-gray-500">−{{ $mockExam->has_negative_marking ? $mockExam->negative_marking_value : '0' }}</div>
        </div>
    </div>

    <div class="mt-4 flex items-center justify-between text-sm">
        <span class="text-gray-400 flex items-center gap-1 text-xs">👥 {{ $mockExam->attempt_count ?? 0 }} attempts</span>
        <span class="inline-flex items-center gap-1 text-brand-primary font-bold text-xs group-hover:underline">
            Start Test <span>→</span>
        </span>
    </div>
</a>
