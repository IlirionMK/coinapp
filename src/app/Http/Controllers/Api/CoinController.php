<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class CoinController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $coins = Cache::remember('coins_list', now()->addMinutes(10), function () {
                Log::channel('coins')->info('[CACHE MISS] coins_list — fetching from DB');

                $result = Coin::select([
                    'id',
                    'name',
                    'symbol',
                    'coingecko_id',
                    'price',
                    'price_change_percentage_24h',
                    'market_cap',
                ])
                    ->orderByDesc('market_cap')
                    ->get()
                    ->map(function ($coin) {
                        $localIconPath = public_path("icons/{$coin->coingecko_id}.png");

                        $iconPath = File::exists($localIconPath)
                            ? "/icons/{$coin->coingecko_id}.png"
                            : "/icons/default.png";

                        return [
                            'id' => $coin->id,
                            'name' => $coin->name,
                            'symbol' => $coin->symbol,
                            'coingecko_id' => $coin->coingecko_id,
                            'price' => $coin->price,
                            'price_change_percentage_24h' => $coin->price_change_percentage_24h,
                            'market_cap' => $coin->market_cap,
                            'icon_path' => $iconPath,
                        ];
                    });

                if ($result->isEmpty()) {
                    Log::channel('coins')->warning('[EMPTY] No coins found in DB.');
                }

                return $result;
            });

            Log::channel('coins')->info('[CACHE HIT] coins_list — returned to client');

            return response()->json($coins);
        } catch (Throwable $e) {
            Log::channel('coins')->error('[ERROR] Failed to fetch coins: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Failed to load coin data'], 500);
        }
    }

    public function convert(Request $request): JsonResponse
    {
        $from = strtolower($request->query('from'));
        $to = strtolower($request->query('to'));
        $amount = floatval($request->query('amount', 1));

        if (!$from || !$to || $amount <= 0) {
            Log::channel('coins')->warning("[CONVERT] Invalid parameters: from={$from}, to={$to}, amount={$amount}");
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $fromPrice = Cache::get("coin:{$from}:price");
        $toPrice = Cache::get("coin:{$to}:price");

        if (!$fromPrice || !$toPrice) {
            Log::channel('coins')->warning("[CONVERT] Missing cache data: from={$fromPrice}, to={$toPrice}");
            return response()->json(['error' => 'Missing coin data'], 404);
        }

        $converted = ($amount * $fromPrice) / $toPrice;

        Log::channel('coins')->info("[CONVERT] {$amount} {$from} = {$converted} {$to}");

        return response()->json(['converted' => round($converted, 8)]);
    }
}
