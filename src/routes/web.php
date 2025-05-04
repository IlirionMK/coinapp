<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

Route::get('/test-log', function () {
    Log::channel('coins')->info('✅ Test log at ' . now());
    return 'Logged!';
});
