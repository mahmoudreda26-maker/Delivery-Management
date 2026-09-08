<?php

namespace App\Listeners;

use App\Events\TrackingSessionEnded;
use App\Models\User;
use App\Notifications\TrackingSessionEndedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTrackingSessionEndedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(TrackingSessionEnded $event): void
    {
        $managers = User::where('role', 'manager')->get();
        foreach ($managers as $manager) {
            $manager->notify(
                new TrackingSessionEndedNotification(
                    $event->trackingSession->driver,
                    $event->trackingSession->vehicle
                )
            );
        }
    }
}
