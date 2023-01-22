<?php

namespace Takemo101\Egg\Routing;

use Takemo101\Egg\Routing\Shared\Domain;
use Takemo101\Egg\Routing\Shared\Name;
use Takemo101\Egg\Routing\Shared\Path;
use Exception;
use Takemo101\Egg\Routing\Shared\Filters;
use Takemo101\Egg\Routing\Shared\Handler;

/**
 * ルート組み立てのためのグループ
 *
 * @mutable
 */
final class RouteGroup
{
    /**
     * @var Path
     */
    private Path $path;

    /**
     * @var Filters
     */
    private Filters $filters;

    /**
     * constructor
     *
     * @param self|null $parent
     * @param array<RouteGroup|RouteNode> $children
     * @param Path|null $path
     * @param Domain|null $domain
     * @param Name|null $name
     */
    public function __construct(
        private readonly ?self $parent = null,
        private array $children = [],
        ?Path $path = null,
        ?Filters $filters = null,
        private ?Domain $domain = null,
        private ?Name $name = null,
    ) {
        $this->path = $path ?? Path::empty();
        $this->filters = $filters ?? Filters::empty();
    }

    /**
     * グループ追加
     *
     * @param self $group
     * @return self
     */
    public function group(self $group): self
    {
        $this->children[] = $group;

        return $this;
    }

    /**
     * ルート追加
     *
     * @param RouteNode $node
     * @return self
     */
    public function node(RouteNode $node): self
    {
        $this->children[] = $node;

        return $this;
    }

    /**
     * パスの追加
     *
     * @param callable $filter
     * @return self
     */
    public function path(string $path): self
    {
        $this->path = new Path($path);

        return $this;
    }

    /**
     * フィルターの追加
     *
     * @param object|array|string $filter
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
        $this->domain = Domain::fromURIString($domain);

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
     * 親のグループを持っているか？
     *
     * @return boolean
     */
    public function hasParent(): bool
    {
        return !empty($this->parent);
    }

    /**
     * 親のグループを取得する
     *
     * @return self
     */
    public function parent(): self
    {
        return $this->parent ?? throw new Exception('error: parent is empty!');
    }

    /**
     * 全てのルートを再起的に取得
     *
     * @return RouteNode[]
     */
    public function nodes(): array
    {
        $nodes = [];

        foreach ($this->children as $child) {
            // RouteGroupの変換
            if ($child instanceof self) {
                $nodes = [
                    ...$nodes,
                    ...$child
                        ->mix($this)
                        ->nodes(),
                ];
            }
            // RouteNodeの変換
            elseif ($child instanceof RouteNode) {
                $nodes[] = $child->mixGroup($this);
            }
        }

        return $nodes;
    }

    /**
     * グループのデータをミックスして
     * 新しいグループを生成
     *
     * @return self
     */
    public function mix(self $group): self
    {
        return new self(
            parent: $this->parent,
            children: $this->children,
            path: $group->mixPath($this->path),
            filters: $group->mixFilters($this->filters),
            domain: $group->mixDomain($this->domain),
            name: $group->mixName($this->name),
        );
    }

    /**
     * mix path
     *
     * @param Path $path
     * @return Path
     */
    public function mixPath(Path $path): Path
    {
        return $this->path->join($path);
    }

    public function mixFilters(Filters $filters): Filters
    {
        return $this->filters->join($filters);
    }

    /**
     * mix domain
     *
     * @return Domain|null
     */
    public function mixDomain(?Domain $domain): ?Domain
    {
        $prefixDomain = $this->domain;

        return $prefixDomain && $domain
            ? $prefixDomain->join($domain)
            : ($prefixDomain ?? $domain);
    }

    /**
     * mix name
     *
     * @return Name|null
     */
    public function mixName(?Name $name): ?Name
    {
        $prefixName = $this->name;

        return $prefixName && $name
            ? $prefixName->join($name)
            : ($prefixName ?? $name);
    }

    /**
     * 空のインスタンスを生成
     *
     * @return self
     */
    public static function empty(): self
    {
        return new self();
    }
}
