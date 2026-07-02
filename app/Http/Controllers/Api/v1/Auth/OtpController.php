<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Auth\VerifyOtpAction;
use App\Http\Requests\Api\v1\Auth\VerifyOtpRequest;
use App\Support\ApiResponse;

class OtpController extends Controller
{

  public function verifyOtp(VerifyOtpRequest $request, VerifyOtpAction $verifyOtpAction)
  {
    $isValid = $verifyOtpAction->execute($request->email, $request->otp);
    if (!$isValid) {
      return ApiResponse::error('The OTP is invalid or has expired.', [], 400);
    }
    return ApiResponse::success([], 'OTP verified successfully.');

  }


}
