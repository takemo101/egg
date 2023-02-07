<?php

namespace Takemo101\Egg\Support\Injector;

use Closure;
use LogicException;

/**
 * DIコンテナでインスタンスを管理するクラス
 */
class Container implements ContainerContract
{
    /**
     * injection bind map
     *
     * @var array<string,DefinitionContract>
     */
    private array $binds = [];

    /**
     * injection aliases
     *
     * @var array<string,string>
     */
    private array $aliases = [];

    /**
     * injection aliases
     *
     * @var array<string,DefinitionLabels>
     */
    private array $classAliases = [];

    /**
     * @var CallableResolver
     */
    private readonly CallableResolver $callableResolver;

    /**
     * @var ObjectResolver
     */
    private readonly ObjectResolver $objectResolver;

    /**
     * @var DefinitionDataFilters
     */
    private readonly DefinitionDataFilters $filters;

    public function __construct(
        ?DefinitionDataFilters $filters = null,
        ?ArgumentResolvers $resolvers = null
    ) {
        $this->filters = $filters ?? new DefinitionDataFilters();

        $resolvers ??= ArgumentResolvers::default();

        $this->callableResolver = new CallableResolver(
            container: $this,
            resolvers: $resolvers,
        );

        $this->objectResolver = new ObjectResolver(
            container: $this,
            resolvers: $resolvers,
        );

        $this->instance(
            DefinitionDataFilters::class,
            $this->filters,
        );
    }

    /**
     * 別名の設定
     * instanceで別名を設定する場合は、
     * instanceよりも先に設定する必要がある
     *
     * @param string $class
     * @param string $alias
     * @return self
     */
    public function alias(string $class, string $alias)
    {
        if ($class === $alias) {
            throw new LogicException("[{$class}] same name as the alias");
        }

        $this->aliases[$alias] = $class;

        /** @var DefinitionLabels */
        $labels = isset($this->classAliases[$class])
            ? $this->classAliases[$class]->add($alias)
            : new DefinitionLabels($class, $alias);

        $this->classAliases[$class] = $labels;

        return $this;
    }

    /**
     * 別名を取得する
     *
     * @param string $alias
     * @return string
     */
    private function toAlias(string $alias): string
    {
        return isset($this->aliases[$alias])
            ? $this->toAlias($this->aliases[$alias])
            : $alias;
    }

    /**
     * 別名ラベルコレクションを取得する
     *
     * @param string $alias
     * @return DefinitionLabels
     */
    private function toLabels(string $alias): DefinitionLabels
    {
        $alias = $this->toAlias($alias);

        return $this->classAliases[$alias] ?? new DefinitionLabels($alias);
    }

    /**
     * インスタンスの依存注入を設定
     *
     * @param string $label
     * @param mixed $instance
     * @return self
     */
    public function instance(string $label, mixed $instance)
    {
        $definition = new InstanceDefinition($instance);

        $this->binds[$label] = $definition;

        $labels = $this->toLabels($label);

        $this->filters->filter(
            labels: $labels,
            definition: $definition,
            data: $instance,
        );

        return $this;
    }

    /**
     * シングルトンでの依存注入を設定
     *
     * @param string $label
     * @param Closure|string|null $callback
     * @return self
     */
    public function singleton(string $label, Closure|string|null $callback = null)
    {
        $callback ??= $label;

        $this->binds[$label] = new Definition($callback, true);

        return $this;
    }

    /**
     * 通常の依存注入を設定
     *
     * @param string $label
     * @param Closure|string|null $callback
     * @return self
     */
    public function bind(string $label, Closure|string|null $callback = null)
    {
        $callback ??= $label;

        $this->binds[$label] = new Definition($callback);

        return $this;
    }

    /**
     * バインドされているか
     *
     * @param string $label
     * @return boolean
     */
    public function has(string $label): bool
    {
        return isset($this->binds[$label]) || isset($this->aliases[$label]);
    }

    /**
     * 全てのバインディングを開放
     *
     * @return self
     */
    public function clear()
    {
        $this->binds = [];
        $this->aliases = [];

        return $this;
    }

    /**
     * クラスまたはラベル名から依存性を解決した値を取得する
     *
     * @param string $label
     * @param mixed[] $options
     * @return mixed
     */
    public function make(string $label, array $options = [])
    {
        $label = $this->toAlias($label);

        if (!isset($this->binds[$label])) {

            if (class_exists($label)) {
                return $this
                    ->bind($label)
                    ->make($label);
            }

            throw new BindingException("not found label or class [{$label}]");
        }

        $definition = $this->binds[$label];

        $builded = $definition->isBuilded();
        $result = $definition->resolve($this->objectResolver, $options);

        // 初期化時にフックを実行
        if (!$builded) {

            // 関連するラベルを取得
            $labels = $this->toLabels($label);

            $result = $this->filters->filter(
                labels: $labels,
                definition: $definition,
                data: $result,
            );
        }

        return $result;
    }

    /**
     * callableから依存性を解決した値を取得する
     *
     * @param callable $callable
     * @param mixed[] $options
     * @return mixed
     */
    public function call(callable $callable, array $options = [])
    {
        return $this->callableResolver->resolve(
            callable: $callable,
            options: $options
        );
    }
}
