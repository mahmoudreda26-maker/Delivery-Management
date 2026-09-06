<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackingSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'driver' => [
                'id' => $this->driver?->id,
                'name' => $this->driver?->name,
            ],

            'vehicle' => [
                'id' => $this->vehicle?->id,
                'plate_number' => $this->vehicle?->plate_number,
            ],

            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}