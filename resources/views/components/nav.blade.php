<nav class="sticky top-0 z-50 glass-card border-b border-gray-200/50 dark:border-gray-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-4 md:gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="hidden md:flex w-10 h-10 rounded-xl bg-gradient-to-br from-brand-primary to-emerald-600 items-center justify-center text-white font-bold text-xl shadow-lg group-hover:shadow-green-500/50 transition-all duration-300 transform group-hover:rotate-6">
                        L
                    </div>
                    <span class="text-xl font-heading font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300">
                        LearnUp
                    </span>
                </a>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                   class="text-gray-600 dark:text-gray-300 hover:text-brand-primary px-3 py-2 rounded-md font-medium transition-colors @if(request()->routeIs('home') && !request()->has('subject')) text-brand-primary @endif">
                    <span>🏠</span> Home
                </a>

                <!-- Courses Dropdown -->
                <div class="relative" x-data="{ coursesOpen: false }" @click.away="coursesOpen = false">
                    <button @click="coursesOpen = !coursesOpen"
                            class="flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-brand-primary px-3 py-2 rounded-md font-medium transition-colors">
                        <span>🎓</span> Courses
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': coursesOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="coursesOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="absolute left-0 mt-2 w-48 rounded-xl bg-white dark:bg-slate-800 shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50"
                         @click="coursesOpen = false">
                        <a href="{{ route('home') }}#subject-grid"
                           class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-brand-primary/10 hover:text-brand-primary font-medium transition-colors rounded-lg mx-1">
                            ⚖️ LAT
                        </a>
                    </div>
                </div>

                <a href="{{ route('mock-exams.index') }}"
                   class="text-gray-600 dark:text-gray-300 hover:text-brand-primary px-3 py-2 rounded-md font-medium transition-colors flex items-center gap-2 @if(request()->routeIs('mock-exams.*')) text-brand-primary @endif">
                    <span>📝</span> Mock Tests
                </a>

                <!-- Auth Links -->
                @auth
                    <div class="flex items-center gap-3" x-data="{ open: false }" @click.away="open = false">
                        <a href="{{ route('home') }}"
                           class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-brand-primary transition-colors font-medium">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#6a63e7] to-[#5147d3] text-sm font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden lg:inline">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit"
                                    class="text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors font-medium text-sm">
                                Log Out
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-600 dark:text-gray-300 hover:text-brand-primary px-3 py-2 rounded-md font-medium transition-colors">
                        Log in
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-brand-primary text-white font-semibold px-4 py-2 rounded-xl hover:bg-green-600 transition-all duration-200 shadow-md hover:shadow-green-500/40">
                        Sign Up
                    </a>
                @endauth

                <!-- Theme Toggle -->
                <button x-on:click="toggle()"
                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg x-show="!isDark" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg x-show="isDark" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-toggle" type="button"
                        class="p-2 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open navigation menu</span>
                    <span class="text-2xl leading-none">☰</span>
                </button>
            </div>
        </div>

        <!-- Mobile Slide-down Menu -->
        <div id="mobile-menu"
             class="md:hidden max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out">
            <div class="mt-2 mb-4 rounded-2xl bg-white dark:bg-slate-900 shadow-xl border border-gray-200/70 dark:border-gray-800 p-3 space-y-1">
                <a href="{{ route('home') }}"
                   class="mobile-menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-primary font-medium transition-colors">
                    <span>🏠</span> <span>Home</span>
                </a>

                <!-- Courses Accordion (Mobile) -->
                <div x-data="{ mobileCoursesOpen: false }">
                    <button @click="mobileCoursesOpen = !mobileCoursesOpen"
                            class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-primary font-medium transition-colors">
                        <div class="flex items-center gap-3">
                            <span>🎓</span> <span>Courses</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': mobileCoursesOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="mobileCoursesOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="ml-4 border-l-2 border-gray-200 dark:border-gray-700 pl-2 space-y-1">
                        <a href="{{ route('home') }}#subject-grid"
                           class="mobile-menu-link flex items-center gap-3 px-4 py-2 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-brand-primary/10 hover:text-brand-primary font-medium transition-colors">
                            ⚖️ LAT
                        </a>
                    </div>
                </div>
                <a href="{{ route('mock-exams.index') }}"
                   class="mobile-menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-primary font-medium transition-colors">
                    <span>📝</span> <span>Mock Tests</span>
                </a>
                <button id="theme-toggle-mobile" type="button"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-primary font-medium transition-colors"
                        x-on:click="toggle()">
                    <span class="text-xl">🌙</span>
                    <span>Dark Mode</span>
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                const isOpen = mobileMenu.classList.contains('max-h-96');
                if (isOpen) {
                    mobileMenu.classList.remove('max-h-96', 'opacity-100');
                    mobileMenu.classList.add('max-h-0', 'opacity-0');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                } else {
                    mobileMenu.classList.remove('max-h-0', 'opacity-0');
                    mobileMenu.classList.add('max-h-96', 'opacity-100');
                    mobileMenuToggle.setAttribute('aria-expanded', 'true');
                }
            });

            document.querySelectorAll('.mobile-menu-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    mobileMenu.classList.remove('max-h-96', 'opacity-100');
                    mobileMenu.classList.add('max-h-0', 'opacity-0');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                });
            });
        }
    });
</script>
