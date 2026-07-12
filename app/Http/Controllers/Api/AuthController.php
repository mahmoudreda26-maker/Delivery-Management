<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;



class AuthController extends Controller
{
    use ApiResponse;


    public function login(LoginRequest $request, AuthService $authService)
    {
        $data =  $authService->login($request->validated());
        return $this->success([
            'user' => new UserResource($data['user']),
            'token' => $data['token'],
        ], 'Login successful');
    }
    public function logout(AuthService $authService)
    {
        $authService->logout();

        return $this->success(null, 'Logout successful');
    }
    public function me(AuthService $authService)
    {

        $user = $authService->me();

        return $this->success(
            new UserResource($user),
            'User data fetched successfully'
        );
    }
}
