<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Vehicle;
use App\Repositories\LocationRepository;

class LocationService
{
    public function __construct(protected LocationRepository $locationRepository) {}
    public function history(array $data)
    {
        return $this->locationRepository->history($data);
    }
    public function latest(Vehicle $vehicle): ?Location
    {
        return $this->locationRepository->latest($vehicle);
    }
    public function latestLocations()
    {
        return $this->locationRepository->latestLocations();
    }
}
