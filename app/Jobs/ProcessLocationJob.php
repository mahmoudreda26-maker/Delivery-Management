<?php

namespace App\Jobs;

use App\Models\Location;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessLocationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $location) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $previousLocation = Location::where(
            'tracking_session_id',
            $this->location->tracking_session_id
        )
            ->where('recorded_at', '<', $this->location->recorded_at)
            ->latest('recorded_at')
            ->latest('id')
            ->first();

        // أول Location في الرحلة
        if (!$previousLocation) {
            return;
        }

        $lat1 = deg2rad($previousLocation->latitude);
        $lon1 = deg2rad($previousLocation->longitude);

        $lat2 = deg2rad($this->location->latitude);
        $lon2 = deg2rad($this->location->longitude);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1)
            * cos($lat2)
            * sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // المسافة بالمتر
        $distance = 6371000 * $c;

        $this->location->trackingSession()->increment(
            'total_distance',
            $distance
        );
    }
}
