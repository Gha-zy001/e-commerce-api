<?php

namespace App\Actions\Auth;

use App\Events\UserRegister;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Devgh\ApiErrorHandler\Facades\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterAction
{
    public function execute(array $data, string $userAgent)
    {
        $user = User::create($data);
        $device = substr($userAgent ?? '', 0, 255);
        $token = $user->createToken('user_token '.$device)->plainTextToken;
        event(new UserRegister($user));
        $user = new UserResource($user);

        return ApiResponse::data([
            'user' => $user,
            'token' => $token,
        ], 'User registered successfully. Please verify your email using the OTP sent to your email.', Response::HTTP_CREATED);
    }
}
