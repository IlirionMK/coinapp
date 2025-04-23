<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\JsonResponse;

class CoinController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Coin::all());
    }
}
