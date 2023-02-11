<?php

namespace Takemo101\Egg\Http\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CsrfTokenMismatchHttpException extends HttpException
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
            statusCode: 419,
            headers: [],
            message: $message ?? 'Csrf token mismatch!',
            previous: $previous,
            code: $code,
        );
    }
}
