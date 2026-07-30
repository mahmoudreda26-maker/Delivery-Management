<?php

namespace App\Events;

use App\Http\Resources\VehicleResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $vehicle;

    public function __construct($vehicle)
    {
       
        $this->vehicle = $vehicle;
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
            'vehicle' => new VehicleResource($this->vehicle)
        ];
    }
}