<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Artisan::call('app:reduce-days-command');
    Artisan::call('app:reduce-expiration-time-command');
    Artisan::call('app:collect-command');
    Artisan::call('app:delete-user-suscription-command');
})->everyTwoMinutes();

/*Schedule::call(function () {
    Artisan::call('app:reduce-days-command');
    Artisan::call('app:reduce-expiration-time-command');
    Artisan::call('app:collect-command');
})->daily();*/
