<?php

namespace Takemo101\Egg\Support\Injector;

use Closure;

/**
 * DIコンテナ
 */
interface ContainerContract
{
    /**
     * 別名の設定
     * instanceで別名を設定する場合は、
     * instanceよりも先に設定する必要がある
     *
     * @param string $class
     * @param string $alias
     * @return self
     */
    public function alias(string $class, string $alias);

    /**
     * インスタンスの依存注入を設定
     *
     * @param string $label
     * @param mixed $instance
     * @return self
     */
    public function instance(string $label, mixed $instance);

    /**
     * シングルトンでの依存注入を設定
     *
     * @param string $label
     * @param Closure|string|null $callback
     * @return self
     */
    public function singleton(string $label, Closure|string|null $callback = null);

    /**
     * 通常の依存注入を設定
     *
     * @param string $label
     * @param Closure|string|null $callback
     * @return self
     */
    public function bind(string $label, Closure|string|null $callback = null);

    /**
     * バインドされているか
     *
     * @param string $label
     * @return boolean
     */
    public function has(string $label): bool;

    /**
     * 全てのバインディングを開放
     *
     * @return self
     */
    public function clear();

    /**
     * クラスまたはラベル名から依存性を解決した値を取得する
     *
     * @param string $label
     * @return mixed
     */
    public function make(string $label);

    /**
     * callableから依存性を解決した値を取得する
     *
     * @param callable $callable
     * @param mixed[] $options
     * @return mixed[]
     */
    public function call(callable $callable, array $options = []);
}
