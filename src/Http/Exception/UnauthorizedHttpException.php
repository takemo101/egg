<?php

namespace Takemo101\Egg\Http\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UnauthorizedHttpException extends HttpException
{
    /**
     * constructor
     *
     * @param string $challenge
     * @param string|null $message
     * @param Throwable|null $previous
     * @param integer $code
     */
    public function __construct(
        string $challenge,
        ?string $message = null,
        Throwable $previous = null,
        int $code = 0,
    ) {
        $headers['WWW-Authenticate'] = $challenge;

        parent::__construct(
            statusCode: Response::HTTP_UNAUTHORIZED,
            headers: $headers,
            message: $message ?? Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
            previous: $previous,
            code: $code,
        );
    }
}
