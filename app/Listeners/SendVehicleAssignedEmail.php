<?php

namespace App\Listeners;

use App\Events\VehicleAssigned;
use App\Mail\VehicleAssignedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVehicleAssignedEmail
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
    public function handle(VehicleAssigned $event): void
    {
        Mail::to($event->user->email)->send(new VehicleAssignedMail($event->user, $event->vehicle));
    }
}
