<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\CurrencyConverterController;
use App\Http\Controllers\Api\CoinSubscriptionController;
use App\Http\Controllers\Api\NewsController;

Route::get('/debug-api-route', function () {
    return response()->json(['message' => 'API route is working']);
});

Route::get('/news', [NewsController::class, 'index']);

Route::get('/coins', [CoinController::class, 'index']);
Route::get('/convert', [CurrencyConverterController::class, 'convert']);

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth:sanctum', 'admin'])->get('/admin-stats', function () {
    return [
        'users' => \App\Models\User::count(),
        'coins' => \App\Models\Coin::count(),
    ];
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/coin-subscriptions', [CoinSubscriptionController::class, 'index']);
    Route::post('/coin-subscriptions', [CoinSubscriptionController::class, 'store']);
    Route::delete('/coin-subscriptions/{coin}', [CoinSubscriptionController::class, 'destroy']);

     Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->noContent();
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->noContent();
    });

});
