<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SyncNewsFromApi;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
});

Artisan::command('log:test', function () {
    Log::info('log:test executed at ' . now());
});


Schedule::command('coins:sync')->everyTenMinutes();

Schedule::command('notify:coin-subscribers')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::job(new SyncNewsFromApi)->everyTenMinutes();

Schedule::command('log:test')->everyTenMinutes();
