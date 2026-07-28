<?php

namespace App\Services;

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
        Request $request,
    ) {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
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
