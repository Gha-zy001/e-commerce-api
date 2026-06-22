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
use App\Services\AuthService;


class RegisterController extends Controller
{

  public function __construct(private AuthService $authService)
  {
  }

  public function register(RegisterRequest $request)
  {
    $this->authService->register($request->validated());
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


}
