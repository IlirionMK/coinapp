<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoinController;

// Список всех монет
Route::get('/coins', [CoinController::class, 'index']);
