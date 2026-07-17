<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'driver_id'    => $this->user_id,
            'plate_number' => $this->plate_number,
            'model'        => $this->model,
            'type'         => $this->type,
            'status'       => $this->status ?? 'idle',
            'year'         => $this->year,
            'created_at'   => $this->created_at ? $this->created_at->toIso8601String() : null,
            
            // Eager Loading
           'driver'       => $this->whenLoaded('driver', function() {
                return [
                    'id'    => $this->driver->id ?? null,
                    'name'  => $this->driver->name ?? null,
                    'phone' => $this->driver->phone ?? null,
                ];
            }),
        ];
    }
}
