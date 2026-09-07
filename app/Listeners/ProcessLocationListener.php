<?php

namespace App\Listeners;

use App\Events\LocationUpdated;
use App\Jobs\ProcessLocationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessLocationListener
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
    public function handle(LocationUpdated $event): void
    {
        // Dispatch the job to process the location
        ProcessLocationJob::dispatch($event->location);
    }
}
