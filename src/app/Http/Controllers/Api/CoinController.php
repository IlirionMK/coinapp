<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class CoinController extends Controller
{
    public function index(): JsonResponse
    {
        $lastUpdated = Coin::max('updated_at');

        if (!$lastUpdated || now()->diffInMinutes($lastUpdated) > 10) {
            // Обновление запускается в фоне (если настроено queue), иначе сразу
            Log::info('Обновляем данные о монетах через команду coins:sync');
            Artisan::call('coins:sync');
        }

        return response()->json(Coin::all());
    }
}
