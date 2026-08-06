@props(['subject'])

<div class="relative group" style="animation-delay: 100ms; animation-fill-mode: forwards;">
    <a href="{{ route('subjects.show', $subject) }}"
       class="block relative h-64 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 opacity-0 animate-fade-in sub-card">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-600 transition-transform duration-500 group-hover:scale-105"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

        @if($subject->icon_svg)
            <div class="absolute top-4 right-4 text-6xl opacity-20 group-hover:scale-110 transition-transform duration-500">
                {!! $subject->icon_svg !!}
            </div>
        @endif

        <div class="absolute bottom-6 left-6 right-6">
            <h3 class="text-2xl font-heading font-bold text-white mb-2">{{ $subject->name }}</h3>
            <p class="text-sm text-white/80 line-clamp-2">{{ $subject->description ?? 'Practice MCQs for competitive exams.' }}</p>
            <div class="flex gap-4 mt-3 text-xs text-white/60">
                <span>{{ $subject->topics_count ?? $subject->topics->count() ?? 0 }} topics</span>
                <span>{{ $subject->topics->sum(fn($t) => $t->question_sets_count ?? 0) }} sets</span>
            </div>
        </div>
        <div class="absolute top-4 left-4 bg-white/20 backdrop-blur-sm rounded-xl px-3 py-1 text-xs font-bold text-white">
            {{ $loop->iteration ?? '' }}
        </div>
    </a>
</div>
