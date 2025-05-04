<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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

        return response()->json($coins);
    }

    public function convert(Request $request): JsonResponse
    {
        $from = strtolower($request->query('from'));
        $to = strtolower($request->query('to'));
        $amount = floatval($request->query('amount', 1));

        if (!$from || !$to || $amount <= 0) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $fromPrice = Cache::get("coin:{$from}:price");
        $toPrice = Cache::get("coin:{$to}:price");

        if (!$fromPrice || !$toPrice) {
            return response()->json(['error' => 'Missing coin data'], 404);
        }

        $converted = ($amount * $fromPrice) / $toPrice;

        return response()->json(['converted' => round($converted, 8)]);
    }
}
