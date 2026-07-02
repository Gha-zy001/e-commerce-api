<?php

namespace App\Services;

use App\Models\User;
use App\Events\CustomerRegistered;
use App\Support\ApiResponse;
use App\Traits\ApiTrait;

class AuthService
{
  use ApiTrait;
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
    return ApiTrait::data([
      'user' => $user,
      'token' => $token,
    ], 'User registered successfully.');
  }
}
