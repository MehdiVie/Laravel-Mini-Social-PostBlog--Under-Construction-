<?php

use App\Mail\RecapEmail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send:recapemail', function () {
    // Trigger the RecapEmail sending
    Mail::to('test@google.com')->send(new RecapEmail());
    $this->info('Recap Email sent successfully!');
})->describe('Send Recap Email');


Artisan::command('test:scheduler', function () {
    \Log::info('Custom scheduler test is running!');
})->describe('Test scheduler command');

Schedule::command('send:recapemail')->everyMinute();

