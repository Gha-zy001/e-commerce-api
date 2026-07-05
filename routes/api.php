<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\RegisterController;
use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\OtpController;
use App\Http\Controllers\Api\v1\Auth\LogoutController;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
  Route::post('register', RegisterController::class);
  Route::post('login', LoginController::class);
  Route::post('verify-otp', [OtpController::class, 'verifyOtp']);

  Route::group([
    'middleware' => 'auth:sanctum',
  ], function () {
    Route::post('logout', LogoutController::class);
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
  });
});