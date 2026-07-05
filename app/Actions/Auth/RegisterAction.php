<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Events\CustomerRegistered;

class RegisterAction
{
  public function execute(array $data): array
  {
    $user = User::create($data);
    // $user->assignRole('customer');
    $token = $user->createToken('auth_token')->plainTextToken;
    event(new CustomerRegistered($user));
    return [
      'user' => $user,
      'token' => $token,
    ];
  }
}
