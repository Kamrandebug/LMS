<!DOCTYPE html>
<html lang="en" class="scroll-smooth @if(request()->cookie('theme') === 'dark') dark @endif"
      x-data="themeManager()"
      x-bind:class="{'dark': isDark}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Prepare for LAT (Law Admission Test) with LearnUp. Free quizzes and study resources.">
    <meta name="theme-color" content="#00C853">

    {!! SEOTools::generate() !!}

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon-32x32.png') }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga.measurement_id', 'G-XXXXXXXXXX') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){ dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', '{{ config("services.ga.measurement_id", "G-XXXXXXXXXX") }}', {
            'anonymize_ip': true
        });
    </script>

    <!-- AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('learnup.adsense.client_id') }}" crossorigin="anonymous" defer></script>

    <!-- Theme FOUC Prevention -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @stack('head')
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-gray-800 dark:text-gray-100 font-sans min-h-screen flex flex-col selection:bg-brand-primary selection:text-white transition-colors duration-300">

    @livewire('daily-fuel-banner')

    @include('components.nav')

    <main class="flex-grow">
        {{ $slot }}
    </main>

    @include('components.footer')

    @livewireScripts

    <script>
        // GA4 Safe Wrapper
        window.trackEvent = function(eventName, params) {
            if (typeof gtag === 'function') {
                gtag('event', eventName, params);
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
