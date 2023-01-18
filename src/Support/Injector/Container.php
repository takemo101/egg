<?php

namespace Takemo101\Egg\Support\Injector;

use Closure;
use LogicException;
use Takemo101\Egg\Support\Injector\Resolver\ArgumentNameResolver;
use Takemo101\Egg\Support\Injector\Resolver\DefaultResolver;

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
     * @var CallableResolver
     */
    private readonly CallableResolver $callableResolver;

    /**
     * @var ObjectResolver
     */
    private readonly ObjectResolver $objectResolver;

    public function __construct(
        ?ArgumentResolvers $resolvers = null
    ) {
        $resolvers = $resolvers ?? new ArgumentResolvers(
            new DefaultResolver(),
            new ArgumentNameResolver(),
        );

        $this->callableResolver = new CallableResolver(
            container: $this,
            resolvers: $resolvers,
        );

        $this->objectResolver = new ObjectResolver(
            container: $this,
            resolvers: $resolvers,
        );
    }

    /**
     * 別名の設定
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

        return $this;
    }

    /**
     * インスタンスの依存注入を設定
     *
     * @param string $label
     * @param mixed $instance
     * @return mixed
     */
    public function instance(string $label, mixed $instance)
    {
        $this->binds[$label] = new InstanceDefinition($instance);

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
        $callback = $callback ?? $label;

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
        $callback = $callback ?? $label;

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
     * @param array $options
     * @return mixed
     */
    public function make(string $label, array $options = [])
    {
        if (!isset($this->binds[$label])) {
            if (isset($this->aliases[$label])) {
                return $this->make($this->aliases[$label]);
            }

            if (class_exists($label)) {
                return $this
                    ->bind($label)
                    ->make($label);
            }

            throw new BindingException("not found label or class [{$label}]");
        }

        $definition = $this->binds[$label];

        return $definition->resolve($this->objectResolver, $options);
    }

    /**
     * callableから依存性を解決した値を取得する
     *
     * @param callable $callable
     * @param array<string,mixed> $options
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
