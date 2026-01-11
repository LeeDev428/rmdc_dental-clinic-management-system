<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule appointment reminders to run every 15 minutes
Schedule::command('appointments:send-reminders')->everyFifteenMinutes();

// Clean up old notifications (older than 7 weeks) - runs daily at midnight
Schedule::command('notifications:cleanup')->daily();
