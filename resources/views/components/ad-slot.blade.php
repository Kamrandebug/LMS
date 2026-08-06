@props(['position' => 'default', 'intent' => null])

@php
    $intent = $intent ?? session('intent', 'browsing');
    $adService = app(\App\Services\AdInjectionService::class);
    $adConfig = $adService->getAdSlot($position, $intent);
    $intentAd = $adService->getIntentBasedAd($intent);
@endphp

<div class="dynamic-ad-slot rounded-2xl overflow-hidden shadow-sm my-4">
    @if($intentAd)
        <a href="{{ $intentAd['link'] }}" target="_blank">
            <img src="{{ asset($intentAd['img']) }}" alt="{{ $intentAd['alt'] }}" class="w-full rounded-xl">
        </a>
    @else
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ $adConfig['client_id'] }}"
             data-ad-slot="{{ $adConfig['slot_id'] }}"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    @endif
</div>
