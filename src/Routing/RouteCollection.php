<?php

namespace Takemo101\Egg\Routing;

use Takemo101\Egg\Routing\Shared\Domain;

/**
 * ルートのコレクション
 *
 * @immutable
 */
final class RouteCollection
{
    /** @var Route[] */
    public readonly array $routes;

    /**
     * constructor
     *
     * @param Route ...$routes
     */
    public function __construct(
        Route ...$routes,
    ) {
        /** @var Route[] */
        $tempRoutes = [];

        foreach ($routes as $route) {
            $tempRoutes[] = $route;
        }

        $this->routes = $tempRoutes;
    }

    /**
     * Builderからインスタンスを生成
     *
     * @param RouteBuilder $builder
     * @param Domain $domain デフォルトドメイン
     * @return self
     */
    public static function fromBuilder(RouteBuilder $builder, Domain $domain): self
    {
        /** @var Route[] */
        $routes = array_map(
            fn (RouteNode $node) => $node->toRoute($domain),
            $builder->root->nodes(),
        );

        return new self(...$routes);
    }
}
