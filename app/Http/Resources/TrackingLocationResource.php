<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackingLocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'vehicle' => [
                'vehicle_id' => $this->vehicle_id,
            ],

            'driver' => [
                'driver_id' => $this->driver_id,
            ],

            'session' => [
                'session_id' => $this->session_id,
            ],

            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'accuracy' => $this->accuracy,
                'speed' => $this->speed,
                'heading' => $this->heading,
                'recorded_at' => $this->recorded_at?->toISOString(),
            ],
        ];
    }
}