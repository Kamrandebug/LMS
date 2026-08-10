<x-layouts.app>
    <main class="flex-grow py-8 max-w-5xl mx-auto w-full px-4">
        <div class="mb-12 relative rounded-3xl bg-brand-dark overflow-hidden p-8 md:p-12 shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-secondary/80 to-brand-primary/80"></div>
            <div class="absolute -right-10 -bottom-10 text-9xl text-white opacity-10 rotate-12 font-heading font-black">
                {{ substr($subject->name, 0, 1) }}
            </div>
            <div class="relative z-10">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center text-white/80 hover:text-white mb-4 text-sm font-bold tracking-wide uppercase transition-colors">
                    &larr; Back to Subjects
                </a>
                <h1 class="text-4xl md:text-5xl font-heading font-extrabold text-white mb-2 leading-tight">
                    {{ $subject->name }}
                </h1>
                <p class="text-white/90 text-lg">Select a topic to begin focusing.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            {{-- Card 1: Resource Material --}}
            <a href="{{ route('subjects.resources.topics', $subject->slug) }}"
               class="group relative rounded-3xl p-8 bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col items-center text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-blue-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl"></div>
                <div class="relative z-10 w-20 h-20 rounded-2xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="relative z-10 text-xl font-heading font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Resource Material</h3>
                <p class="relative z-10 text-sm text-gray-500 dark:text-gray-400 mb-4">Topic notes, PDFs, and study guides</p>
                <span class="relative z-10 inline-flex items-center gap-1 text-sm font-bold text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-all duration-300 group-hover:translate-x-0 -translate-x-2">
                    Browse <span class="text-lg leading-none">&rarr;</span>
                </span>
            </a>

            {{-- Card 2: Practice Tests --}}
            <a href="{{ route('subjects.practice.topics', $subject->slug) }}"
               class="group relative rounded-3xl p-8 bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col items-center text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 via-green-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl"></div>
                <div class="relative z-10 w-20 h-20 rounded-2xl bg-brand-primary/10 dark:bg-brand-primary/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <h3 class="relative z-10 text-xl font-heading font-bold text-gray-900 dark:text-white mb-2 group-hover:text-brand-primary transition-colors">Practice Tests</h3>
                <p class="relative z-10 text-sm text-gray-500 dark:text-gray-400 mb-4">MCQs organized by topic and set</p>
                <span class="relative z-10 inline-flex items-center gap-1 text-sm font-bold text-brand-primary opacity-0 group-hover:opacity-100 transition-all duration-300 group-hover:translate-x-0 -translate-x-2">
                    Start Practice <span class="text-lg leading-none">&rarr;</span>
                </span>
            </a>

            {{-- Card 3: Recorded Lectures --}}
            <a href="{{ route('subjects.lectures.topics', $subject->slug) }}"
               class="group relative rounded-3xl p-8 bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col items-center text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-purple-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl"></div>
                <div class="relative z-10 w-20 h-20 rounded-2xl bg-brand-secondary/10 dark:bg-brand-secondary/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-brand-secondary dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                    </svg>
                </div>
                <h3 class="relative z-10 text-xl font-heading font-bold text-gray-900 dark:text-white mb-2 group-hover:text-brand-secondary dark:group-hover:text-purple-400 transition-colors">Recorded Lectures</h3>
                <p class="relative z-10 text-sm text-gray-500 dark:text-gray-400 mb-4">Watch video lectures by topic</p>
                <span class="relative z-10 inline-flex items-center gap-1 text-sm font-bold text-brand-secondary dark:text-purple-400 opacity-0 group-hover:opacity-100 transition-all duration-300 group-hover:translate-x-0 -translate-x-2">
                    Watch Now <span class="text-lg leading-none">&rarr;</span>
                </span>
            </a>
        </div>
    </main>
</x-layouts.app>
