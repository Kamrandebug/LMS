<x-layouts.app>
    <main class="flex-grow py-8 max-w-5xl mx-auto w-full px-4">
        {{-- Breadcrumb --}}
        <div class="mb-8">
            <a href="{{ route('home') }}"
               class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-brand-primary dark:hover:text-brand-primary text-sm font-medium transition-colors">
                &larr; Home
            </a>
            <span class="mx-2 text-gray-300 dark:text-gray-600">&rsaquo;</span>
            <a href="{{ route('subjects.show', $subject->slug) }}"
               class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-brand-primary dark:hover:text-brand-primary text-sm font-medium transition-colors">
                {{ $subject->name }}
            </a>
            <span class="mx-2 text-gray-300 dark:text-gray-600">&rsaquo;</span>
            <span class="text-brand-primary text-sm font-bold">Practice Tests</span>
        </div>

        {{-- Back button --}}
        <a href="{{ route('subjects.show', $subject->slug) }}"
           class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-brand-primary dark:hover:text-brand-primary text-sm font-medium transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to {{ $subject->name }}
        </a>

        {{-- Page title --}}
        <h1 class="text-3xl md:text-4xl font-heading font-extrabold text-gray-900 dark:text-white mb-8">
            {{ $subject->name }} &mdash; Practice Tests
        </h1>

        {{-- Topic list or empty state --}}
        @if($topics->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800">
                <div class="text-6xl mb-6">📝</div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">No Practice Sets</h2>
                <p class="text-gray-500 dark:text-gray-400">No practice sets available yet for this subject.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($topics as $topic)
                    <a href="{{ route('topics.show', [$subject->slug, $topic->slug]) }}"
                       class="group glass-card rounded-2xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-heading font-bold text-gray-900 dark:text-white group-hover:text-brand-primary transition-colors">
                                {{ $topic->name }}
                            </h3>
                            <span class="badge bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 mt-1">
                                {{ $topic->question_sets_count }} set(s) available
                            </span>
                        </div>
                        <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 group-hover:text-brand-primary group-hover:translate-x-1 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @endforeach
            </div>
        @endif
    </main>
</x-layouts.app>
