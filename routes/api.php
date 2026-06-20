<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group([
  'prefix' => 'v1',
  'controller' => AuthController::class,
  'as' => 'auth.',
  'middleware' => 'api',
], function () {
  Route::post('register', 'register')->name('register');
  Route::post('login', 'login')->name('login');
  Route::post('logout', 'logout')->name('logout');
});