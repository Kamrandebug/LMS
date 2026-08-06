@props(['topic', 'subject'])

<div class="glass-card rounded-2xl p-6 md:p-8">
    <h2 class="text-2xl font-heading font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-3">
        <span class="w-2 h-8 rounded-full bg-brand-primary"></span>
        {{ $topic->name }}
    </h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($topic->questionSets as $set)
            <a href="{{ route('sets.show', ['subject' => $subject, 'topic' => $topic, 'setNumber' => $set->set_number]) }}"
               class="set-card relative group flex flex-col items-center justify-center p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:border-brand-primary/30 dark:hover:border-brand-primary/50 transition-all duration-300 hover:-translate-y-1"
               data-set-id="{{ $subject->slug }}-{{ $topic->slug }}-set-{{ $set->set_number }}">
                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1 group-hover:text-brand-primary transition-colors">Set</span>
                <span class="text-3xl font-heading font-extrabold text-gray-700 dark:text-gray-100 group-hover:text-gray-900 dark:group-hover:text-white">{{ $set->set_number }}</span>
                <span class="text-[10px] bg-brand-light dark:bg-slate-800 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded-full mt-2 font-bold group-hover:bg-brand-primary/10 group-hover:text-brand-primary transition-colors">
                    {{ $set->questions_count ?? $set->question_count }} MCQs
                </span>
                <div class="check-icon hidden absolute -top-2 -right-2 w-6 h-6 bg-brand-primary text-white rounded-full flex items-center justify-center shadow-lg animate-pop">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</div>
