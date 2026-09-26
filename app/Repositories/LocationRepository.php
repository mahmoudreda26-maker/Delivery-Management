<?php

namespace App\Repositories;

use App\Models\Location;
use App\Models\Vehicle;

class LocationRepository
{
    public function history(array $data)
    {
        return Location::forVehicle($data['vehicle_id'])->forDate($data['date'])->oldest()->get();
    }

    public function latest(Vehicle $vehicle): ?Location
    {
        return Location::where('vehicle_id', $vehicle->id)
            ->orderByDesc('recorded_at')
            ->orderByDesc('id')
            ->first();
    }

    public function latestLocations()
    {
        return Location::query()
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)
                    ->from('locations as newer')
                    ->whereColumn(
                        'newer.vehicle_id',
                        'locations.vehicle_id'
                    )
                    ->where(function ($query) {
                        $query->whereColumn(
                            'newer.recorded_at',
                            '>',
                            'locations.recorded_at'
                        )
                            ->orWhere(function ($query) {
                                $query->whereColumn(
                                    'newer.recorded_at',
                                    '=',
                                    'locations.recorded_at'
                                )
                                    ->whereColumn(
                                        'newer.id',
                                        '>',
                                        'locations.id'
                                    );
                            });
                    });
            })
            ->get();
    }
}
