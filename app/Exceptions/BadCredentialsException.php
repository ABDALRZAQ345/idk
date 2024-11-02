<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BadCredentialsException extends Exception
{

    public function __construct(string $message = 'Bad credentials')
    {
        parent::__construct($message);
    }
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], Response::HTTP_UNAUTHORIZED);
    }
}
