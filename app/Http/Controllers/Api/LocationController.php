<?php

namespace App\Http\Controllers\Api;

<<<<<<< Updated upstream
=======
use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Vehicle;
use App\Services\LocationService;
>>>>>>> Stashed changes
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Services\TrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    use ApiResponse;
<<<<<<< Updated upstream
   
    public function __construct(protected TrackingService $trackingService) {}

    public function store(StoreLocationRequest $request): JsonResponse
    {
        $user = Auth::user(); 

        $location = $this->trackingService->updateAndBroadcastLocation($user, $request->validated());

     return $this->success(
            $location, 
            'Location updated and broadcasted successfully.', 
            200
        );
    }
=======
    public function store(LocationRequest $request , LocationService $locationService){
     $data =   $locationService->store($request->validated());

   
        $vehicle = Vehicle::where('user_id', auth('api')->id())->first();

        if ($vehicle) {
                event(new LocationUpdated($vehicle));
            }
       
return $this->success(
    new LocationResource($data),
    'Operation successful',
    201
);
     }
>>>>>>> Stashed changes
}
