<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DailyFuelService;
use Illuminate\Http\JsonResponse;

class DailyFuelController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $service = app(DailyFuelService::class);
            $quote = $service->getTodaysQuote();

            return response()->json([
                'success' => true,
                'data' => [
                    'quote' => $quote->quote,
                    'author' => $quote->author,
                    'type' => $quote->type,
                    'badge_color' => $service->getBadgeColor($quote->type),
                    'emoji' => $service->getTypeEmoji($quote->type),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [
                    'quote' => 'Start where you are. Use what you have. Do what you can.',
                    'author' => null,
                    'type' => 'motivation',
                    'badge_color' => 'text-brand-primary',
                    'emoji' => '⚡',
                ],
            ]);
        }
    }
}
