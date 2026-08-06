<x-layouts.app>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-96 bg-brand-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Hero Section Wrapper -->
        <div class="min-h-[calc(100vh-100px)] flex flex-col justify-center py-12 md:py-20">
            <!-- Hero -->
            <div class="text-center mb-16 animate-float">
                <h1 class="text-5xl md:text-6xl font-heading font-extrabold text-gray-900 dark:text-white mb-6 tracking-tight leading-tight">
                    Master Your <br class="hidden md:block" />
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                        LAT Preparation
                    </span>
                </h1>
                <p class="text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium mb-8">
                    Free MCQs for LAT (Law Admission Test).
                    <span class="text-brand-primary font-bold">Gamified. Focused. Free.</span>
                </p>

                <!-- Stats -->
                <div class="flex justify-center gap-8 md:gap-16 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-heading font-extrabold text-gray-900 dark:text-white">3,600+</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">MCQs</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-heading font-extrabold text-gray-900 dark:text-white">6</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Subjects</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-heading font-extrabold text-gray-900 dark:text-white">100%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Free</div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('mock-exams.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-brand-primary text-white font-bold hover:bg-green-600 transition-all shadow-lg shadow-green-500/30 active:scale-95">
                    📝 Start Mock Tests
                </a>
            </div>
        </div>

        <!-- Subjects Section -->
        <section id="subject-grid" class="scroll-mt-24 pb-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mb-4">
                    Explore Subjects
                </h2>
                <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($subjects as $subject)
                    <x-subject-card :subject="$subject" />
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.app>
