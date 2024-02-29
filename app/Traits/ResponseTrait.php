<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ResponseTrait
{
    public function error(string $message, int $code = 422, ?Arrayable $errors = null): JsonResponse
    {
        $response = [
            'error' => [
                'code' => $code,
                'message' => $message,
            ]
        ];

        if ($errors) $response['error']['errors'] = $errors;

        return response()->json($response, $code);
    }
}
