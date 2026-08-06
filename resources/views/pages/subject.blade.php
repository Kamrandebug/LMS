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

        <div class="grid gap-8">
            @forelse($subject->topics as $topic)
                <x-topic-card :topic="$topic" :subject="$subject" />
            @empty
                <div class="text-center py-16">
                    <div class="text-6xl mb-6">📚</div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">Coming Soon</h2>
                    <p class="text-gray-500">Topics for this subject are being added.</p>
                </div>
            @endforelse
        </div>
    </main>
</x-layouts.app>
