<?php

use App\Console\Commands\SportSrcTest;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sportsrc:test', function () {
    $this->call(SportSrcTest::class);
})->describe('Run SportSRC API connectivity and response validation tests.');
