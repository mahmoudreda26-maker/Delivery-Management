<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\LoginHistoryServiec;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginHistoryController extends Controller
{
    use ApiResponse;


    public function index(LoginHistoryServiec $loginHistoryService)
    {
        $user = Auth::user();

        $history = $loginHistoryService->getUserHistory($user);

        return $this->success([
            'history' => $history
        ]);
    }


    public function store(Request $request, LoginHistoryServiec $loginHistoryService)
    {
        $user = Auth::user();

        $history = $loginHistoryService->store($user, $request);

        return $this->success(
            $history,
            'Login history created successfully'
        );
    }



    public function show( LoginHistoryServiec $loginHistoryService)
    {
        $user = Auth::user();

        $history = $loginHistoryService->getLastLogin($user);

        return $this->success([
            'last_login' => $history
        ]);
    }



    public function update( LoginHistoryServiec $loginHistoryService)
    {
        $user = Auth::user();

        $loginHistoryService->updateLogout($user);

        return $this->success(
            null,
            'Logout time updated successfully'
        );
    }

    public function destroy(LoginHistoryServiec $loginHistoryService)
    {
        $deleted = $loginHistoryService->deleteOldHistory();

        return $this->success([
            'deleted_records' => $deleted
        ]);
    }
}
