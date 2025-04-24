<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CoinController extends Controller
{
    public function index(): JsonResponse
    {
        $wasRecentlySynced = Cache::get('coins_recently_synced');

        if (!$wasRecentlySynced) {
            Log::info('Обновляем данные о монетах через coins:sync');

            // Запоминаем, что обновление было
            Cache::put('coins_recently_synced', true, now()->addMinutes(10));

            // Запускаем команду (можно заменить на dispatch в будущем)
            Artisan::queue('coins:sync');
        }

        return response()->json(Coin::all());
    }
}
