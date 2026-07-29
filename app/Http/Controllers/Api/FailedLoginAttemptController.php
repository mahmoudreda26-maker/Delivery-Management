<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FailedLoginAttemptService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;


class FailedLoginAttemptController extends Controller
{
    use ApiResponse;
    
    public function index(FailedLoginAttemptService $failedLoginAttemptService)
    {
        $user = Auth::user();


        $attempts = $failedLoginAttemptService->getUserAttempts($user->email);
        return $this->paginated($attempts);
    }

    public function  destory(FailedLoginAttemptService $failedLoginAttemptService)
    {
        $deleted = $failedLoginAttemptService->deleteOldAttempts();

        return $this->success([
            'deleted_records' => $deleted
        ]);
    }
}
