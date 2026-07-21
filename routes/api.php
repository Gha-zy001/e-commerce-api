<?php

use App\Http\Controllers\Api\v1\Auth\User\ForgetPasswordController;
use App\Http\Controllers\Api\v1\Auth\User\LoginController;
use App\Http\Controllers\Api\v1\Auth\User\LogoutController;
use App\Http\Controllers\Api\v1\Auth\User\OtpController;
use App\Http\Controllers\Api\v1\Auth\User\RegisterController;
use App\Http\Controllers\Api\v1\Auth\User\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong',
    ]);
});
Route::prefix('v1')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::group(['middleware' => 'throttle:5,1'], function () {
        Route::post('/forgot-password', ForgetPasswordController::class);
        Route::post('/verify-otp', OtpController::class);
        Route::post('/reset-password', ResetPasswordController::class);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', LogoutController::class);
    });
});
