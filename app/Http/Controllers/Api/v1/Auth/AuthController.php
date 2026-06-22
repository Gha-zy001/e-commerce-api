<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Actions\Auth\SendOtpAction;
use App\Actions\Auth\VerifyOtpAction;
use App\Events\CustomerRegistered;


class AuthController extends Controller
{
  public function register(RegisterRequest $request)
  {
    $user = User::create($request->validated());
    $user->assignRole('customer');
    // $sendOtpAction->execute($user);
    $token = $user->createToken('auth_token')->plainTextToken;
    event(new CustomerRegistered($user));
    return response()->json([
      'message' => 'User created successfully. Please check your email for the OTP.',
      'user' => $user,
      'token' => $token,
    ]);
  }


  public function sendOtp(Request $request, SendOtpAction $sendOtpAction)
  {
    $request->validate(['email' => 'required|email|exists:users,email']);

    $user = User::where('email', $request->email)->first();
    $sendOtpAction->execute($user);
    return response()->json([
      'message' => 'OTP sent successfully to your email.'
    ]);
  }

  public function verifyOtp(Request $request, VerifyOtpAction $verifyOtpAction)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email',
      'otp' => 'required|numeric'
    ]);

    $isValid = $verifyOtpAction->execute($request->email, $request->otp);

    if (!$isValid) {
      return response()->json([
        'message' => 'The OTP is invalid or has expired.'
      ], 400);
    }

    return response()->json([
      'message' => 'OTP verified successfully.'
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
