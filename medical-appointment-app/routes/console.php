<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('appointments:send-daily-reports')
    ->dailyAt('12:55')
    ->timezone('America/Merida')
    ->withoutOverlapping();

Schedule::command('appointments:send-reminders')
    ->dailyAt('12:55')
    ->timezone('America/Merida')
    ->withoutOverlapping();
