<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

app(Schedule::class)->command('courses:remove-expired')->daily(); //Exécution quotidienne à minuit
 //app(Schedule::class)->command('certificates:expire')->everyDay(); Exécution quotidienne à minuit


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
