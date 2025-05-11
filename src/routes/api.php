<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\CurrencyConverterController;
use App\Http\Controllers\Api\CoinSubscriptionController;

Route::get('/coins', [CoinController::class, 'index']);
Route::get('/convert', [CurrencyConverterController::class, 'convert']);

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::middleware(['auth:sanctum', 'admin'])->get('/admin-stats', function () {
    return ['users' => \App\Models\User::count(), 'coins' => \App\Models\Coin::count()];
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/coin-subscriptions', [CoinSubscriptionController::class, 'index']);
    Route::post('/coin-subscriptions', [CoinSubscriptionController::class, 'store']);
});
