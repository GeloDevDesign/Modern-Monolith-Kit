<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Schedule::command('inspire')->hourly();
Schedule::command('backup:run --only-db')->daily()->at('01:00');
// Schedule::command('backup:run --only-files')->monthly(); // gawing per week
Schedule::command('backup:run --only-files')->weekly()->fridays()->at('3:00'); // gawing per week

