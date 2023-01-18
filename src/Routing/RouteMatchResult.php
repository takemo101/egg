<?php

namespace Takemo101\Egg\Routing;

use Takemo101\Egg\Routing\Shared\Handler;
use Takemo101\Egg\Routing\Shared\HttpMethod;
use Takemo101\Egg\Routing\Shared\RouteAction;

/**
 * ルートのマッチ結果
 */
final class RouteMatchResult
{
    /**
     * constructor
     *
     * @param string $uri
     * @param HttpMethod $method
     * @param RouteAction $action
     * @param array<string,mixed> $parameters
     * @param string|null $name
     */
    public function __construct(
        public readonly string $uri,
        public readonly HttpMethod $method,
        public readonly RouteAction $action,
        public readonly array $parameters = [],
        public readonly ?string $name = null,
    ) {
        //
    }
}
