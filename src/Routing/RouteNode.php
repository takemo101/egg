<?php

namespace Takemo101\Egg\Routing;

use Takemo101\Egg\Routing\Shared\Domain;
use Takemo101\Egg\Routing\Shared\Filters;
use Takemo101\Egg\Routing\Shared\HttpMethods;
use Takemo101\Egg\Routing\Shared\Name;
use Takemo101\Egg\Routing\Shared\Path;
use Takemo101\Egg\Routing\Shared\RouteAction;
use Takemo101\Egg\Routing\Shared\URN;
use Takemo101\Egg\Support\Shared\Handler;

/**
 * ルート組み立てのためのノード
 */
final class RouteNode
{
    /**
     * @var Filters
     */
    private Filters $filters;

    /**
     * constructor
     *
     * @param HttpMethods $methods
     * @param Path $path
     * @param Handler $handler
     * @param Domain|null $domain
     * @param Name|null $name
     */
    public function __construct(
        private readonly HttpMethods $methods,
        private readonly Path $path,
        private readonly Handler $handler,
        ?Filters $filters = null,
        private ?Domain $domain = null,
        private ?Name $name = null,
    ) {
        $this->filters = $filters ?? Filters::empty();
    }

    /**
     * フィルターの追加
     *
     * @param object|mixed[]|string $filter
     * @return self
     */
    public function filter(object|array|string ...$filter): self
    {
        $this->filters = $this->filters->join(
            Filters::fromPrimitives(...$filter),
        );

        return $this;
    }

    /**
     * ドメインを追加
     *
     * @param string $domain
     * @return self
     */
    public function domain(string $domain): self
    {
        $this->domain = new Domain($domain);

        return $this;
    }

    /**
     * 名前を追加
     *
     * @param string $name
     * @return self
     */
    public function name(string $name): self
    {
        $this->name = new Name($name);

        return $this;
    }

    /**
     * グループのデータをミックスして
     * 新しいインスタンスを生成する
     *
     * @param RouteGroup $group
     * @return self
     */
    public function mixGroup(RouteGroup $group): self
    {
        return new self(
            methods: $this->methods,
            path: $group->mixPath($this->path),
            handler: $this->handler,
            filters: $group->mixFilters($this->filters),
            domain: $group->mixDomain($this->domain),
            name: $group->mixName($this->name),
        );
    }

    /**
     * ルートインスタンスに変換する
     *
     * @param Domain $domain デフォルトドメイン
     * @return Route
     */
    public function toRoute(Domain $domain): Route
    {
        $urn =  new URN(
            path: $this->path,
            domain: $this->domain ?? $domain,
        );

        return new Route(
            urn: $urn,
            methods: $this->methods,
            action: new RouteAction(
                handler: $this->handler,
                filters: $this->filters,
            ),
            name: $this->name,
        );
    }
}
