<footer class="bg-gray-900 border-t border-gray-800/60 text-gray-300">
    {{-- Main footer body --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

            {{-- Brand --}}
            <div class="flex flex-col">
                <img src="{{ asset('images/logo-white.png') }}" alt="LearnUp"
                     class="h-8 w-auto object-contain mb-4"
                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                <span class="text-white font-heading font-bold text-xl mb-4 hidden">LearnUp</span>

                <p class="text-gray-400 leading-relaxed text-sm max-w-xs">
                    LearnUp is an Ed-Tech platform that is committed to making quality education accessible
                    and affordable to all. Our mission is to democratize education by breaking down physical,
                    financial, and geographical barriers to education.
                </p>
            </div>

            {{-- Contact Us --}}
            <div class="flex flex-col">
                <h3 class="font-heading font-bold text-white text-base mb-6 tracking-wide">Contact Us</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 flex items-center justify-center shrink-0 text-base leading-none mt-px">📍</span>
                        <span class="leading-relaxed">{!! nl2br(e($contactInfo['address'])) !!}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 flex items-center justify-center shrink-0 text-base leading-none">📞</span>
                        <div class="flex flex-col">
                            <a href="https://wa.me/923001234567" target="_blank"
                               class="hover:text-green-400 transition-colors duration-300">
                                {{ $contactInfo['phone_primary'] }}
                            </a>
                            <a href="https://wa.me/923217654321" target="_blank"
                               class="hover:text-green-400 transition-colors duration-300">
                                {{ $contactInfo['phone_secondary'] }}
                            </a>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 flex items-center justify-center shrink-0 text-base leading-none">✉️</span>
                        <a href="mailto:contact@example.com"
                           class="hover:text-white transition-colors duration-300">contact@example.com</a>
                    </li>
                </ul>
            </div>

            {{-- Join Community --}}
            <div class="flex flex-col">
                <h3 class="font-heading font-bold text-white text-base mb-6 tracking-wide">Join Community</h3>
                <div class="space-y-3">
                    <a href="https://wa.me/923001234567" target="_blank"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-700/50 bg-gray-800/30
                              text-gray-400 hover:text-green-400 hover:border-green-500/30 hover:bg-gray-800/60
                              transition-all duration-300 group">
                        <span class="w-5 h-5 flex items-center justify-center shrink-0 text-lg leading-none group-hover:scale-110 transition-transform duration-300">💬</span>
                        <span class="font-medium text-sm">WhatsApp</span>
                    </a>
                    <a href="https://youtube.com/@example" target="_blank"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-700/50 bg-gray-800/30
                              text-gray-400 hover:text-red-400 hover:border-red-500/30 hover:bg-gray-800/60
                              transition-all duration-300 group">
                        <span class="w-5 h-5 flex items-center justify-center shrink-0 text-lg leading-none group-hover:scale-110 transition-transform duration-300">▶️</span>
                        <span class="font-medium text-sm">YouTube</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-gray-800/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500 items-center text-center md:text-left">
                <p>&copy; {{ date('Y') }} LearnUp. All rights reserved.</p>
                <div class="flex justify-center gap-6">
                    <a href="{{ route('privacy') }}" class="hover:text-gray-300 transition-colors duration-300">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-gray-300 transition-colors duration-300">Terms of Service</a>
                </div>
                <p class="flex items-center justify-center md:justify-end gap-1">
                    Made with <span class="text-red-400">❤️</span> in Pakistan
                </p>
            </div>
        </div>
    </div>
</footer>
