<?php

namespace Takemo101\Egg\Http\Exception;

use RuntimeException;
use Throwable;

/**
 * Httpの基本例外
 */
class HttpException extends RuntimeException implements HttpExceptionContract
{
    /**
     * constructor
     *
     * @param integer $statusCode
     * @param array<string,mixed> $headers
     * @param string $message
     * @param Throwable|null $previous
     * @param integer $code
     */
    public function __construct(
        private readonly int $statusCode,
        private readonly array $headers = [],
        string $message = '',
        Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous,
        );
    }

    /**
     * Httpステータスコードを取得する
     *
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Httpステータスコードを取得する
     *
     * @return array<string,mixed>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
