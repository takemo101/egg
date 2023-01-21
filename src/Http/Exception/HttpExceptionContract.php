<?php

namespace Takemo101\Egg\Http\Exception;

/**
 * Httpの例外
 */
interface HttpExceptionContract
{
    /**
     * Httpステータスコードを取得する
     *
     * @return integer
     */
    public function getStatusCode(): int;

    /**
     * レスポンスヘッダーを取得
     *
     * @return array<string,mixed>
     */
    public function getHeaders(): array;
}
