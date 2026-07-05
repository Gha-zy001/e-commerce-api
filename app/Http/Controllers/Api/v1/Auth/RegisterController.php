<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;
use App\Actions\Auth\RegisterAction;
use Devgh\ApiErrorHandler\Facades\ApiResponse;

class RegisterController extends Controller
{
  public function __invoke(RegisterRequest $request, RegisterAction $action)
  {
    $result = $action->execute($request->validated());
    return ApiResponse::data($result, 'User registered successfully.');
  }
}
