<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoinController;

Route::get('/coins', [CoinController::class, 'index']);
