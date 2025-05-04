<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\ConvertController;

Route::get('/convert', [ConvertController::class, 'convert']);
Route::get('/coins', [CoinController::class, 'index']);
Route::get('/convert', [CoinController::class, 'convert']);

