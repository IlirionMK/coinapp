<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\ProxyImageController;

//Route::get('/proxy/image', ProxyImageController::class);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');




