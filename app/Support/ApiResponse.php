<?php
namespace App\Support;

class ApiResponse
{
    public static function success($data = [], string $message = '', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => [],
        ], $code);
    }

    public static function error($message = '', $errors = [], int $code = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
            'errors' => $errors,
        ], $code);
    }
}