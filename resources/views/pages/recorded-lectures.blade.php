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
            <a href="{{ route('subjects.lectures.topics', $subject->slug) }}"
               class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-brand-primary dark:hover:text-brand-primary text-sm font-medium transition-colors">
                Recorded Lectures
            </a>
            <span class="mx-2 text-gray-300 dark:text-gray-600">&rsaquo;</span>
            <span class="text-brand-primary text-sm font-bold">{{ $topic->name }}</span>
        </div>

        {{-- Back button --}}
        <a href="{{ route('subjects.lectures.topics', $subject->slug) }}"
           class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-brand-primary dark:hover:text-brand-primary text-sm font-medium transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Topics
        </a>

        {{-- Page title --}}
        <h1 class="text-3xl md:text-4xl font-heading font-extrabold text-gray-900 dark:text-white mb-8">
            {{ $topic->name }} &mdash; Recorded Lectures
        </h1>

        {{-- Lectures list or empty state --}}
        @if($lectures->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800">
                <div class="text-6xl mb-6">🎬</div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">No Lectures Yet</h2>
                <p class="text-gray-500 dark:text-gray-400">No lectures have been uploaded for this topic yet.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($lectures as $lecture)
                    <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all duration-300">
                        <div class="mb-4">
                            <h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-2">
                                {{ $lecture->title }}
                            </h3>
                            @if($lecture->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                    {{ $lecture->description }}
                                </p>
                            @endif
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="badge bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                    {{ ucfirst($lecture->platform) }}
                                </span>
                                @if($lecture->duration_minutes)
                                    <span class="badge bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400">
                                        {{ $lecture->duration_minutes }} min
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- YouTube embed --}}
                        @if($lecture->platform === 'youtube')
                            <div class="relative w-full rounded-xl overflow-hidden bg-black" style="padding-bottom: 56.25%;">
                                <iframe
                                    class="absolute inset-0 w-full h-full"
                                    src="{{ $lecture->getEmbedUrl() }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    loading="lazy"
                                ></iframe>
                            </div>
                        @endif

                        <a href="{{ $lecture->video_url }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 mt-4 text-sm font-bold text-brand-secondary dark:text-purple-400 hover:underline transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                            Watch on {{ ucfirst($lecture->platform) }}
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</x-layouts.app>
