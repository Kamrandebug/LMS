<x-layouts.app>
    <main class="flex-grow py-8 max-w-5xl mx-auto w-full px-4">
        <div class="mb-8">
            <a href="{{ route('subjects.show', $subject) }}"
               class="inline-flex items-center text-gray-500 hover:text-brand-primary mb-4 text-sm font-bold tracking-wide uppercase transition-colors">
                &larr; Back to {{ $subject->name }}
            </a>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-2 h-10 rounded-full bg-brand-primary"></span>
                <div>
                    <h1 class="text-3xl md:text-4xl font-heading font-extrabold text-gray-900 dark:text-white">
                        {{ $topic->name }}
                    </h1>
                    <p class="text-gray-500 text-sm">{{ $topic->subject->name }}</p>
                </div>
            </div>
        </div>

        <x-topic-card :topic="$topic" :subject="$subject" />
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const completed = JSON.parse(localStorage.getItem('completed_sets') || '[]');
            completed.forEach(id => {
                const el = document.querySelector(`.set-card[data-set-id="${id}"] .check-icon`);
                if (el) el.classList.remove('hidden');
            });
        });
    </script>
    @endpush
</x-layouts.app>
