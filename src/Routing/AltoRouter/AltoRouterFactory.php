<?php

namespace Takemo101\Egg\Routing\AltoRouter;

use AltoRouter;
use Takemo101\Egg\Routing\RouteBuilder;
use Takemo101\Egg\Routing\RouteCollection;
use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Routing\RouterFactoryContract;
use Takemo101\Egg\Routing\Shared\Domain;
use Takemo101\Egg\Routing\Shared\HttpMethod;
use Takemo101\Egg\Routing\Shared\HttpMethods;
use RuntimeException;
use Takemo101\Egg\Routing\Shared\StringHelper;

/**
 * AltoRouterを生成する
 */
final class AltoRouterFactory implements RouterFactoryContract
{
    /**
     * @var string
     */
    public const HttpMethodSeparator = '|';

    /**
     * constructor
     *
     * @param string $baseURL
     * @param array<string,string> $matchTypes
     */
    public function __construct(
        private readonly string $baseURL,
        private readonly array $matchTypes = [],
    ) {
        //
    }

    /**
     * RouteBuilderからRouterを生成する
     *
     * @param RouteBuilder $builder
     * @return Router
     */
    public function create(RouteBuilder $builder): RouterContract
    {
        $routes = RouteCollection::fromBuilder(
            builder: $builder,
            domain: Domain::fromURIString($this->baseURL),
        );

        $router = new AltoRouter(
            basePath: StringHelper::parseProtocol($this->baseURL),
            matchTypes: $this->matchTypes,
        );

        foreach ($routes->routes as $route) {
            $router->map(
                method: $this->createHttpMethodsString($route->methods),
                route: $route->urn->toString(),
                target: $route->action,
                name: $route->name?->name,
            );
        }

        return new AltoRouterAdapter($router);
    }

    /**
     * HttpMethodsをAltoRouterで使用する文字列に変換する
     *
     * @param HttpMethods $methods
     * @return string
     */
    private function createHttpMethodsString(HttpMethods $methods): string
    {
        /** @var string[] */
        $methodStrings = array_map(
            fn (HttpMethod $method) => $this->toHttpMethodString($method),
            $methods->methods,
        );

        return implode(
            self::HttpMethodSeparator,
            $methodStrings,
        );
    }

    /**
     * HttpMethodをAltoRouterで使用する文字列に変換する
     *
     * @param HttpMethod $method
     * @return string
     */
    private function toHttpMethodString(HttpMethod $method): string
    {
        return strtoupper($method->value);
    }
}
