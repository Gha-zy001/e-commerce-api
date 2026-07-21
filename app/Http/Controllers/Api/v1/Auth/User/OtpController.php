<?php

namespace App\Http\Controllers\Api\v1\Auth\User;

use App\Actions\Auth\User\VerifyOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\VerifyOtpRequest;
use Devgh\ApiErrorHandler\Facades\ApiResponse;

class OtpController extends Controller
{
    public function __invoke(VerifyOtpRequest $request, VerifyOtpAction $verifyOtpAction)
    {

        $type = $request->input('type', 'email_verification');

        $isValid = $verifyOtpAction->execute($request->email, $request->otp, $type);
        if (! $isValid) {
            return ApiResponse::error([], 'The OTP is invalid or has expired.', 400);
        }

        return ApiResponse::success(ucfirst(str_replace('_', ' ', $type)).' verified successfully.');
    }
}
