<?php

namespace App\Services;

use App\Models\User;
use App\Events\CustomerRegistered;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }
    public function register(array $data)
    {
        $user = User::create($data);
        $token = $user->createToken('auth_token')->plainTextToken;
        event(new CustomerRegistered($user));
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
