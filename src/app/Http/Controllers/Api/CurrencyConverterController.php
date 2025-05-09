<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\Request;

class CurrencyConverterController extends Controller
{
    public function convert(Request $request)
    {
        $from = strtoupper($request->input('from'));
        $to = strtoupper($request->input('to'));
        $amount = floatval($request->input('amount'));

        if (!$from || !$to || $amount <= 0) {
            return response()->json(['error' => 'Invalid input'], 422);
        }

        $fromCoin = Coin::where('symbol', $from)->first();
        $toCoin = Coin::where('symbol', $to)->first();

        if (!$fromCoin || !$toCoin) {
            return response()->json(['error' => 'Coin not found'], 404);
        }

        if (!$fromCoin->price || !$toCoin->price) {
            return response()->json(['error' => 'Missing price data'], 500);
        }

        $converted = $amount * ($fromCoin->price / $toCoin->price);

        return response()->json([
            'converted' => round($converted, 8),
            'from_symbol' => $fromCoin->symbol,
            'to_symbol' => $toCoin->symbol,
            'rate' => round($fromCoin->price / $toCoin->price, 8),
        ]);
    }
}
