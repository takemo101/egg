<?php

namespace Takemo101\Egg\Http\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InternalServerErrorHttpException extends HttpException
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
            statusCode: Response::HTTP_INTERNAL_SERVER_ERROR,
            headers: [],
            message: $message ?? Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            previous: $previous,
            code: $code,
        );
    }
}
