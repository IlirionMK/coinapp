<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\JsonResponse;

class CoinController extends Controller
{
    public function index(): JsonResponse
    {
        $coins = Coin::select([
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
                return [
                    'id' => $coin->id,
                    'name' => $coin->name,
                    'symbol' => $coin->symbol,
                    'coingecko_id' => $coin->coingecko_id,
                    'price' => $coin->price,
                    'price_change_percentage_24h' => $coin->price_change_percentage_24h,
                    'market_cap' => $coin->market_cap,
                    'icon_path' => "/icons/{$coin->coingecko_id}.png",
                ];
            });

        return response()->json($coins);
    }
}
