<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MessageException extends Exception
{
    public function __construct($message = '', $code = Response::HTTP_INTERNAL_SERVER_ERROR, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return self
     */
    public static function dontHavePermission(): self
    {
        return new self(
            'Member does not have permission to manage messages.',
            Response::HTTP_UNAUTHORIZED,
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
