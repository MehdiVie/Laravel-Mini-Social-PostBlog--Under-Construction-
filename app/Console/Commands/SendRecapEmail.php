<?php

namespace App\Console\Commands;

use App\Mail\RecapEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRecapEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:recapemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Recap Email every minute';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Send the recap email
        Mail::to('test@google.com')->send(new RecapEmail());
        $this->info('Recap Email sent successfully!');
    }
}
