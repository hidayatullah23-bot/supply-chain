<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('countries:sync-intelligence --limit=25')->dailyAt('01:00')->withoutOverlapping();
Schedule::command('countries:sync-profiles')->weekly()->withoutOverlapping();
Schedule::command('countries:sync-currencies')->dailyAt('00:30')->withoutOverlapping();
Schedule::command('countries:sync-news --limit=40 --only-baseline')->dailyAt('02:00')->withoutOverlapping();
Schedule::command('disruptions:derive-news')->dailyAt('03:00')->withoutOverlapping();
Schedule::command('ports:sync-wpi --replace')->monthly()->withoutOverlapping();
