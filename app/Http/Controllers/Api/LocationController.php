<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use ApiResponse;
    public function store(LocationRequest $request , LocationService $locationService){
     $data =   $locationService->store($request->validated());
       
return $this->success(
    new LocationResource($data),
    'Operation successful',
    201
);
     }
}
