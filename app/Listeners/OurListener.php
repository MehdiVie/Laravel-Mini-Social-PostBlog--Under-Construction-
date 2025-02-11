<?php

namespace App\Listeners;

use App\Events\OurEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OurListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OurEvent $event): void
    {
        //
        Log::debug("user {$event->username} just performed {$event->action}");
    }
}
