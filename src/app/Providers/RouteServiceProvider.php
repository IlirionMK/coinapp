<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // API маршруты
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php')); // ← путь с учётом твоей структуры

        // WEB маршруты
        Route::middleware('web')
            ->group(base_path('routes/web.php')); // ← тоже

        // Можно добавить кастомные, если нужно
    }
}
