<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    Auth::login($request->user());
    return redirect('/email-verified');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email-verified', function () {
    return view('app');
});

Route::get('/test-log', function () {
    Log::channel('coins')->info('✅ Test log at ' . now());
    return 'Logged!';
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
