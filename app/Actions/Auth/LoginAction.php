<?php

namespace App\Actions\Auth;

use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Devgh\ApiErrorHandler\Facades\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginAction
{
    public function execute(string $email, string $password, string $userAgent)
    {
        $key = 'login:'.$email;

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($key, 60);

            return ApiResponse::error([], __('auth.failed'), 401);
        }

        RateLimiter::clear($key);

        $device = substr($userAgent ?? '', 0, 255);
        $token = $user->createToken('user_token '.$device)->plainTextToken;

        return ApiResponse::data([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful.');
    }
}
