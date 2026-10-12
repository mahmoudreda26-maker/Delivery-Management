<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vehicle' => [
                'vehicle_id' => $this->vehicle_id,
                'plate_number' => $this->vehicle->plate_number,
            ],

            'driver' => [
                'driver_id' => $this->user_id,
                'driver_name' => $this->user->name,
            ],

            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'accuracy' => $this->accuracy,
                'speed' => $this->speed,
                'heading' => $this->heading,
                'recorded_at' => $this->recorded_at,
            ],
        ];
    }
}
