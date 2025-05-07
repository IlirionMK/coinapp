<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CoinController;

 Route::middleware('guest')->post('/login', [AuthController::class, 'login']);
Route::middleware('guest')->post('/register', [AuthController::class, 'register']);

 Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

 Route::get('/coins', [CoinController::class, 'index']);
