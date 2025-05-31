<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationController;


Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app' => config('app.name'),
        'environment' => config('app.env'),
    ]);
});

Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)
    ->middleware(['signed'])
    ->name('verification.verify');

Route::get('/reset-password/{token}', function () {
    return view('app');
})->name('password.reset');

Route::get('/email-verified', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');

