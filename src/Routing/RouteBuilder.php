<?php

namespace Takemo101\Egg\Routing;

use Takemo101\Egg\Routing\Shared\Domain;
use Takemo101\Egg\Support\Shared\Handler;
use Takemo101\Egg\Routing\Shared\HttpMethod;
use Takemo101\Egg\Routing\Shared\HttpMethods;
use Takemo101\Egg\Routing\Shared\Path;

/**
 * ルートを組み立てるためのクラス
 *
 * @mutable
 */
final class RouteBuilder
{
    /**
     * @var RouteGroup
     */
    private RouteGroup $current;

    /**
     * constructor
     *
     * @param RouteGroup $root ルートツリーのroot
     * @param array<string,string> $matchTypes
     */
    public function __construct(
        public RouteGroup $root = new RouteGroup(),
        private array $matchTypes = [],
    ) {
        $this->current = $root;
    }

    /**
     * グルーピング
     *
     * @param callable $callback
     * @return RouteGroup
     */
    public function group(callable $callback): RouteGroup
    {
        $group = new RouteGroup(parent: $this->current);

        // 新しいのグループをカレントに設定する
        $this->current->group($group);
        $this->current = $group;

        call_user_func($callback, $this);

        // グルーピングが完了した親のグループをカレントに設定する
        if ($group->hasParent()) {
            $this->current = $group->parent();
        }

        return $group;
    }

    /**
     * ルートのマッピング
     *
     * @param HttpMethod[] $methods
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function map(
        array $methods,
        string $path,
        object|array|string $handler,
    ): RouteNode {
        $node = new RouteNode(
            methods: new HttpMethods(...$methods),
            path: new Path($path),
            handler: new Handler($handler),
        );

        $this->current->node($node);

        return $node;
    }

    /**
     * create get method route
     *
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function get(string $path, object|array|string $handler): RouteNode
    {
        return $this->map(HttpMethod::toGetMethods(), $path, $handler);
    }

    /**
     * create post method route
     *
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function post(string $path, object|array|string $handler): RouteNode
    {
        return $this->map([HttpMethod::Post], $path, $handler);
    }

    /**
     * create put method route
     *
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function put(string $path, object|array|string $handler): RouteNode
    {
        return $this->map([HttpMethod::Put], $path, $handler);
    }

    /**
     * create delete method route
     *
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function delete(string $path, object|array|string $handler): RouteNode
    {
        return $this->map([HttpMethod::Delete], $path, $handler);
    }

    /**
     * create patch method route
     *
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function patch(string $path, object|array|string $handler): RouteNode
    {
        return $this->map([HttpMethod::Patch], $path, $handler);
    }

    /**
     * create options method route
     *
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function options(string $path, object|array|string $handler): RouteNode
    {
        return $this->map([HttpMethod::Options], $path, $handler);
    }

    /**
     * create any method route
     *
     * @param string $path
     * @param object|mixed[]|string $handler
     * @return RouteNode
     */
    public function any(string $path, object|array|string $handler): RouteNode
    {
        return $this->map(HttpMethod::cases(), $path, $handler);
    }

    /**
     * create all route
     *
     * @param object|mixed[]|string $handler
     * @return void
     */
    public function nothing(object|array|string $handler): void
    {
        $this->root->node(new RouteNode(
            methods: new HttpMethods(...HttpMethod::cases()),
            path: new Path(),
            handler: new Handler($handler),
            domain: Domain::nothing(),
        ));
    }

    /**
     * マッチタイプを追加する
     *
     * @param array<string,string> $matchTypes
     * @return self
     */
    public function addMatchTypes(array $matchTypes): self
    {
        $this->matchTypes = array_merge(
            $this->matchTypes,
            $matchTypes,
        );

        return $this;
    }

    /**
     * マッチタイプを取得する
     *
     * @return array<string,string>
     */
    public function matchTypes(): array
    {
        return $this->matchTypes;
    }
}
