<?php

namespace App\Traits;

use App\Enums\HttpStatusCode;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(array $data, string $message = '', int $code): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ], $code);
    }

    protected function error(string $message, int $code): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
        ], $code);
    }
}
