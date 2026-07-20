<?php

namespace App\Http\Requests\Api\v1\Auth;

use Devgh\ApiErrorHandler\Facades\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ];
    }

    public function authenticate($data)
    {
        $key = 'login:'.$data;

        if (RateLimiter::tooManyAttempts($key, 1)) {
            return ApiResponse::error([], 'Too many requests. Please slow down.', 429);
        }
    }
}
