<div @if(!$visible) x-data="{ dismissed: true }" x-init="dismissed = true" @endif>
    @if($visible && $quote)
        <div id="brain-fuel-banner"
             class="w-full bg-slate-900 text-white text-xs md:text-sm py-2 text-center relative z-[60] transition-all duration-500"
             x-data="{ dismissed: false }"
             x-show="!dismissed">
            <div class="max-w-4xl mx-auto px-4 flex items-center justify-center space-x-2 animate-fade-in">
                <span class="hidden sm:inline-block text-xs font-bold uppercase tracking-wider opacity-70 {{ $this->badgeColor }} mr-2">
                    {{ $type }}
                </span>
                <span class="text-sm font-medium opacity-90">
                    "{{ $quote }}"
                </span>
                @if($author)
                    <span class="text-xs text-gray-400">— {{ $author }}</span>
                @endif
                <button wire:click="dismiss" class="text-gray-500 hover:text-white ml-2 transition-colors">
                    ✕
                </button>
            </div>
        </div>
    @endif
</div>
