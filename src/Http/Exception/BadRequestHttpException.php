<?php

namespace Takemo101\Egg\Http\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BadRequestHttpException extends HttpException
{
    /**
     * constructor
     *
     * @param string|null $message
     * @param Throwable|null $previous
     * @param integer $code
     */
    public function __construct(
        ?string $message = null,
        Throwable $previous = null,
        int $code = 0,
    ) {
        parent::__construct(
            statusCode: Response::HTTP_BAD_REQUEST,
            headers: [],
            message: $message ?? Response::$statusTexts[Response::HTTP_BAD_REQUEST],
            previous: $previous,
            code: $code,
        );
    }
}
