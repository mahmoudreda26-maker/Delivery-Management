<?php

namespace App\Services;

use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(
        array $credentials,
        RefreshTokenService $refreshTokenService,
        LoginHistoryServiec $loginHistoryServiec,
        FailedLoginAttemptService $failedLoginAttemptService,
        Request $request,
    ) {
        if (!Auth::attempt($credentials)) {
            $failedLoginAttemptService->store(
                $credentials['email'],
                $request->ip(),
                $request->userAgent(),
                'invalid_credentials'
            );

            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }


        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;
        //  SendWelcomeEmail::dispatch();
        $loginHistoryServiec->store($user, $request);

        $refreshToken = $refreshTokenService->issue($user);

        return [
            'user' => $user,
            'token' => $token,
            'refresh_token' => $refreshToken,
        ];
    }


    public function logout(
        RefreshTokenService $refreshTokenService
    ) {
        $user = auth()->user();

        $refreshTokenService->revoke($user);

        if ($user) {
            $user->currentAccessToken()->delete();
        }
    }


    public function me()
    {

        return auth()->user();
    }
}
