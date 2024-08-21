<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationException extends Exception
{
    public function __construct($message = '', $code = Response::HTTP_INTERNAL_SERVER_ERROR, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return self
     */
    public static function invalidCredentials(): self
    {
        return new self(
            'Invalid credentials',
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @return self
     */
    public static function unauthorized(): self
    {
        return new self(
            'Unauthorized',
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()
            ->json([
                'message' => $this->getMessage(),
            ], $this->getCode());
    }
}
