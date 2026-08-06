<x-layouts.app>
    <div class="flex-grow flex items-center justify-center py-16">
        <div class="text-center max-w-md mx-auto px-4">
            <div class="text-8xl mb-6">🔍</div>
            <h1 class="text-4xl font-heading font-extrabold text-gray-900 dark:text-white mb-4">Page Not Found</h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 mb-8">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <a href="{{ route('home') }}"
               class="inline-block bg-brand-primary text-white font-bold py-3 px-8 rounded-full hover:shadow-lg hover:-translate-y-1 transition-all">
                Go Home
            </a>
        </div>
    </div>
</x-layouts.app>
