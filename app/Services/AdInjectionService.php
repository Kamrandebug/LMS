<?php

namespace App\Services;

class AdInjectionService
{
    public function getAdSlot(string $position, ?string $intent = null): array
    {
        $clientId = config('learnup.adsense.client_id');
        $slotId = config('learnup.adsense.slots.' . $position, 'XXXXXXXXXX');

        // If user has intent, we can customize ads
        $intent = $intent ?? session('intent', 'browsing');

        return [
            'client_id' => $clientId,
            'slot_id' => $slotId,
            'position' => $position,
            'intent' => $intent,
        ];
    }

    public function shouldShowAds(): bool
    {
        // Always show ads - the platform is free
        return true;
    }

    public function getIntentBasedAd(string $intent): ?array
    {
        return match ($intent) {
            'lat' => [
                'img' => '/images/lat_ad.jpeg',
                'link' => 'https://wa.me/923281638036',
                'alt' => 'LAT Preparation Course',
            ],
            'jobs' => [
                'img' => '/images/eop-ad.jpg',
                'link' => 'https://wa.me/923425509729?text=I%20saw%20your%20ad%20on%20your%20website%20and%20I%20am%20interested%20in%20your%20OnePaper%20Course.',
                'alt' => 'OnePaper Course',
            ],
            default => null,
        };
    }
}
