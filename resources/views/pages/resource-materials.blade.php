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
            <a href="{{ route('subjects.resources.topics', $subject->slug) }}"
               class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-brand-primary dark:hover:text-brand-primary text-sm font-medium transition-colors">
                Resource Material
            </a>
            <span class="mx-2 text-gray-300 dark:text-gray-600">&rsaquo;</span>
            <span class="text-brand-primary text-sm font-bold">{{ $topic->name }}</span>
        </div>

        {{-- Back button --}}
        <a href="{{ route('subjects.resources.topics', $subject->slug) }}"
           class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-brand-primary dark:hover:text-brand-primary text-sm font-medium transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Topics
        </a>

        {{-- Page title --}}
        <h1 class="text-3xl md:text-4xl font-heading font-extrabold text-gray-900 dark:text-white mb-8">
            {{ $topic->name }} &mdash; Study Materials
        </h1>

        {{-- Materials list or empty state --}}
        @if($materials->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800">
                <div class="text-6xl mb-6">📄</div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">No Materials Yet</h2>
                <p class="text-gray-500 dark:text-gray-400">No materials have been uploaded for this topic yet. Check back soon.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($materials as $material)
                    <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $material->title }}
                                </h3>
                                @if($material->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">
                                        {{ $material->description }}
                                    </p>
                                @endif
                                <span class="badge bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                    {{ strtoupper($material->file_type) }}
                                </span>
                            </div>
                            <a href="{{ $material->getStorageUrl() }}" target="_blank" rel="noopener"
                               class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-all duration-200 hover:shadow-lg shadow-blue-500/20">
                                Open {{ strtoupper($material->file_type) }}
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</x-layouts.app>
