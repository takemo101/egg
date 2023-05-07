<?php

namespace Takemo101\Egg\Routing;

use RuntimeException;

/**
 * ルートを選択する
 */
interface RouterContract
{
    /**
     * URIとHTTPメソッドからルートの結果を取得
     *
     * @param string $uri
     * @param string $method
     * @return RouteMatchResult|null
     */
    public function match(
        string $uri,
        string $method,
    ): ?RouteMatchResult;

    /**
     * 名前からルートURIを取得
     *
     * @param string $name
     * @param array<string,mixed> $parameter
     * @return string
     * @throws RuntimeException
     */
    public function route(
        string $name,
        array $parameter = [],
    ): string;
}
