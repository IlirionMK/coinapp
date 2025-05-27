<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\CurrencyConverterController;
use App\Http\Controllers\Api\CoinSubscriptionController;
use App\Http\Controllers\Api\NewsController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminUserController;

// Public auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

// Public API routes
Route::get('/debug-api-route', fn () => response()->json(['message' => 'API route is working']));
Route::get('/news', [NewsController::class, 'index']);
Route::get('/coins', [CoinController::class, 'index']);
Route::get('/convert', [CurrencyConverterController::class, 'convert']);
Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authenticated user routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Coin subscriptions
    Route::get('/coin-subscriptions', [CoinSubscriptionController::class, 'index']);
    Route::post('/coin-subscriptions', [CoinSubscriptionController::class, 'store']);
    Route::delete('/coin-subscriptions/{coin}', [CoinSubscriptionController::class, 'destroy']);
    Route::put('/coin-subscriptions/{coin}', [CoinSubscriptionController::class, 'update']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    Route::get('/profile/settings', [ProfileController::class, 'getSettings']);
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings']);

    // Email verification
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->noContent();
        }

        $request->user()->sendEmailVerificationNotification();
        return response()->noContent();
    });

    // Admin-only routes
    Route::middleware(['verified', 'admin'])->group(function () {
        Route::get('/admin-stats', fn () => [
            'users' => \App\Models\User::count(),
            'coins' => \App\Models\Coin::count(),
        ]);

        Route::get('/admin/users', [AdminUserController::class, 'index']);
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);
        Route::put('/admin/users/{user}/ban', [AdminUserController::class, 'toggleBan']);
        Route::put('/admin/users/{user}/verify', [AdminUserController::class, 'verifyEmail']);
    });
});
