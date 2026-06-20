<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
  public function register(RegisterRequest $request)
  {
    $user = User::create($request->validated());
    $token = $user->createToken('auth_token')->plainTextToken;
    $user->assignRole('customer');
    return response()->json([
      'message' => 'User created successfully',
      'user' => $user,
      'token' => $token,
    ]);
  }

  public function login(Request $request)
  {

  }

  public function logout(Request $request)
  {

  }

  public function refresh(Request $request)
  {


  }

  public function me(Request $request)
  {

  }
}
