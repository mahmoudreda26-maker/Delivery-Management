<?php

namespace App\Listeners;

use App\Events\TrackingSessionStarted;
use App\Models\User;
use App\Notifications\TrackingSessionStartedNotification;

class SendTrackingSessionStartedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(TrackingSessionStarted $event): void {
        $managers = User::where('role' , 'manager')->get();

        foreach($managers as $manager){
            $manager->notify(
                new TrackingSessionStartedNotification(
                    $event->trackingSession->driver,
                    $event->trackingSession->vehicle
                )
            );
        }
    }
}
