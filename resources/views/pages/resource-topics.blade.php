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
            <span class="text-brand-primary text-sm font-bold">Resource Material</span>
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
            {{ $subject->name }} &mdash; Resource Material
        </h1>

        {{-- Topic list -- always shown, with per-topic badge --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($topics as $topic)
                <a href="{{ route('subjects.resources.show', [$subject->slug, $topic->slug]) }}"
                   class="group glass-card rounded-2xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-heading font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $topic->name }}
                        </h3>
                        @if($topic->resource_materials_count > 0)
                            <span class="badge bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 mt-1">
                                {{ $topic->resource_materials_count }} material(s)
                            </span>
                        @else
                            <span class="badge bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 mt-1">
                                No materials yet
                            </span>
                        @endif
                    </div>
                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-1 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @empty
                <div class="col-span-full text-center py-20 bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800">
                    <div class="text-6xl mb-6">📂</div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">No Topics Found</h2>
                    <p class="text-gray-500 dark:text-gray-400">No topics found for this subject.</p>
                </div>
            @endforelse
        </div>
    </main>
</x-layouts.app>
