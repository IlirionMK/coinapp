<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CoinController extends Controller
{
    public function index(): JsonResponse
    {
        // Ключ для кэша
        $cacheKey = 'coins:list';

        $coins = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return Coin::select([
                'id',
                'name',
                'symbol',
                'image',
                'price',
                'price_change_percentage_24h',
                'market_cap'
            ])
                ->orderByDesc('market_cap')
                ->get()
                ->map(function ($coin) {
                    $coin->price = (float) $coin->price;
                    $coin->price_change_percentage_24h = (float) $coin->price_change_percentage_24h;
                    return $coin;
                });
        });

        return response()->json($coins);
    }
}
