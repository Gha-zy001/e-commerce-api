<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Auth\VerifyOtpAction;



class OtpController extends Controller
{



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
