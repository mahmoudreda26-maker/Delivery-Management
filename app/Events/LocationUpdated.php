<?php

namespace App\Events;

use App\Http\Resources\TrackingResource;
use App\Models\Location;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('vehicles'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'LocationUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'location' => new TrackingResource($this->location),
        ];
    }
}