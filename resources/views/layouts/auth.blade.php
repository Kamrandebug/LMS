<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sign In — LearnUp')</title>
    <meta name="description" content="Sign in or create a free account on LearnUp — the smartest way to prepare for the LAT (Law Admission Test).">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="font-sans min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 via-slate-50 to-indigo-100/70 px-4 py-6 sm:px-6 lg:px-10">

    @php
        // Whether the card should start flipped — derive it from the current URL
        // so /register shows the register face and /login the login face.
        $authFlipped = request()->is('register') || request()->is('register/*');
    @endphp

    <div class="w-full max-w-5xl mx-auto flex flex-col items-center justify-center min-h-screen">

        {{-- Flash status messages (password reset link sent, verification link sent, etc.) --}}
        @if (session('status'))
            <div class="w-full mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                ✅ {{ session('status') }}
            </div>
        @endif

        {{-- Back to home --}}
        <a href="{{ route('home') }}"
           class="mb-3 inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-[#6a63e7] transition-colors self-start">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
            </svg>
            Back to home
        </a>

        @hasSection('simple-card')
            {{-- ============ SIMPLE CENTERED CARD (forgot/reset/confirm/verify) ============ --}}
            <div class="w-full max-w-md">
                <div class="rounded-3xl bg-white shadow-[0_25px_50px_-12px_rgba(0,0,0,0.4)] p-8 sm:p-10">
                    <div class="mb-6 flex flex-col items-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#6a63e7] to-[#5147d3] shadow-lg shadow-[#6a63e7]/30">
                            <span class="text-2xl font-bold text-white">L</span>
                        </div>
                        @yield('simple-card')
                    </div>
                </div>
            </div>
        @else
            {{-- ============ FLIP CARD (login front / register back) ============ --}}
            <div class="flip-wrapper w-full">
                <div class="flip-card {{ $authFlipped ? 'is-flipped' : '' }} h-auto w-full rounded-3xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.4)]"
                     x-data="{ flipped: {{ $authFlipped ? 'true' : 'false' }}, showPassword: false, init() { if (window.location.pathname.includes('/register')) { this.flipped = true; } }, flip() { this.flipped = !this.flipped; } }"
                     :class="{ 'is-flipped': flipped }">

                    <div class="flip-card-inner">
                        {{-- -------- FRONT FACE : LOGIN -------- --}}
                        <div class="flip-face flip-face-front flex min-h-0 flex-col md:flex-row">
                            <div class="panel-left flex min-h-0 flex-1 flex-col bg-white px-6 py-8 lg:px-10 lg:py-10">
                                @include('auth.partials.login-form')
                            </div>
                            <div class="hero-panel sticky top-0 flex min-h-0 flex-[1.35] flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-[#020b30] via-[#101e5c] to-[#1c41a8] px-8 py-10 text-center">
                                @include('auth.partials.hero-panel')
                            </div>
                        </div>

                        {{-- -------- BACK FACE : REGISTER -------- --}}
                        <div class="flip-face flip-face-back flex min-h-0 flex-col md:flex-row">
                            <div class="panel-left flex min-h-0 flex-1 flex-col bg-white px-6 py-8 lg:px-10 lg:py-10">
                                @include('auth.partials.register-form')
                            </div>
                            <div class="hero-panel sticky top-0 flex min-h-0 flex-[1.35] flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-[#020b30] via-[#101e5c] to-[#1c41a8] px-8 py-10 text-center">
                                @include('auth.partials.hero-panel')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Footer note --}}
        <p class="mt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} LearnUp ·
            <a href="{{ route('privacy') }}" class="hover:text-gray-500 transition-colors">Privacy</a> ·
            <a href="{{ route('terms') }}" class="hover:text-gray-500 transition-colors">Terms</a>
        </p>
    </div>

</body>
</html>
