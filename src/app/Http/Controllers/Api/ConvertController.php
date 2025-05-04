<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\Request;

class ConvertController extends Controller
{
    public function convert(Request $request)
    {
        $fromSymbol = strtolower($request->query('from'));
        $toSymbol = strtolower($request->query('to'));
        $amount = floatval($request->query('amount'));

        if ($fromSymbol === $toSymbol || $amount <= 0) {
            return response()->json(['converted' => $amount]);
        }

        $fromCoin = Coin::where('symbol', $fromSymbol)->first();
        $toCoin = Coin::where('symbol', $toSymbol)->first();

        if (!$fromCoin || !$toCoin || $fromCoin->price <= 0 || $toCoin->price <= 0) {
            return response()->json(['error' => 'Invalid coins'], 400);
        }

        $converted = ($amount * $fromCoin->price) / $toCoin->price;

        return response()->json([
            'converted' => round($converted, 6),
        ]);
    }
}
