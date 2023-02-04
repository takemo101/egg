<?php

namespace Takemo101\Egg\Routing\AltoRouter;

use AltoRouter;
use Takemo101\Egg\Routing\RouteMatchResult;
use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Routing\Shared\HttpMethod;
use Takemo101\Egg\Routing\Shared\RouteAction;
use RuntimeException;

final class AltoRouterAdapter implements RouterContract
{
    /**
     * constructor
     *
     * @param AltoRouter $router
     */
    public function __construct(
        private readonly AltoRouter $router,
    ) {
        //
    }

    /**
     * URIとHTTPメソッドからルートの結果を取得
     *
     * @param string $uri
     * @param string $method
     * @return RouteMatchResult|null
     * @throws RuntimeException
     */
    public function match(
        string $uri,
        string $method,
    ): ?RouteMatchResult {
        $route = $this->router->match($uri, $method);

        if (!$route) {
            return null;
        }

        /** @var mixed */
        $action = $route['target']
            ?? throw new RuntimeException('error! target is not found');

        // target には RouteAction が入っている想定なので
        // それ以外の型が入っていた場合はエラー
        if (!($action instanceof RouteAction)) {
            throw new RuntimeException('error! target is not RouteAction');
        }

        /** @var array<string,mixed> */
        $parameters = $route['params'] ?? [];

        /** @var string */
        $name = $route['name'] ?? null;

        return new RouteMatchResult(
            uri: $uri,
            method: HttpMethod::fromString($method),
            action: $action,
            parameters: $parameters,
            name: $name,
        );
    }

    /**
     * 名前からルートURIを取得
     *
     * @param string $name
     * @param array<string,mixed> $parameters
     * @return string
     * @throws RuntimeException
     */
    public function route(
        string $name,
        array $parameters = [],
    ): string {
        return $this->router->generate($name, $parameters);
    }
}
