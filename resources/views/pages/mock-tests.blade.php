<x-layouts.app>
    <div class="bg-slate-900 pt-16 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-brand-primary/10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-secondary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        <div class="max-w-5xl mx-auto px-4 relative z-10 text-center">
            <div class="inline-flex items-center justify-center p-3 bg-white/10 backdrop-blur-md rounded-2xl mb-8 border border-white/10 shadow-2xl">
                <span class="text-4xl">📝</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-heading font-extrabold text-white mb-6 leading-tight">
                Mock Test Series
            </h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                Timed mock tests for LAT (Law Admission Test). Test your readiness under real exam conditions.
            </p>
        </div>
    </div>

    <main class="max-w-5xl mx-auto px-4 -mt-16 relative z-20 pb-20">
        @if($mockExams->count() > 0)
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($mockExams as $mockExam)
                    <x-mock-exam-card :mockExam="$mockExam" />
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800">
                <div class="text-6xl mb-6">📝</div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">No Mock Tests Yet</h2>
                <p class="text-gray-500">Mock tests are being prepared. Check back soon!</p>
            </div>
        @endif
    </main>
</x-layouts.app>
