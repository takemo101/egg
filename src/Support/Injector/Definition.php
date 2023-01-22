<?php

namespace Takemo101\Egg\Support\Injector;

use Closure;
use Error;
use RuntimeException;

/**
 * 依存注入するための定義
 */
final class Definition implements DefinitionContract
{
    /**
     * created instance
     *
     * @var mixed
     */
    private $instance = null;

    /**
     * construct
     *
     * @param Closure|string $callback
     * @param boolean $singleton
     */
    public function __construct(
        private readonly Closure|string $callback,
        private readonly bool $singleton = false
    ) {
        //
    }

    /**
     * 定義を値に解決
     *
     * @param ObjectResolver $resolver
     * @param array<string,mixed> $options
     * @return mixed
     */
    public function resolve(
        ObjectResolver $resolver,
        array $options = [],
    ) {
        if ($this->singleton) {
            if (is_null($this->instance)) {
                $this->instance = $this->build($resolver, $options);
            }

            return $this->instance;
        }

        return $this->build($resolver, $options);
    }

    /**
     * インスタンス生成
     *
     * @param ObjectResolver $resolver,
     * @param array<string,mixed> $options
     * @throws Error
     * @return mixed
     */
    private function build(
        ObjectResolver $resolver,
        array $options = [],
    ) {
        if ($this->callback instanceof Closure) {
            $result = ($this->callback)($resolver->container());

            if (is_null($result)) {
                throw new Error('error: build type error!');
            }
        } else {
            $result = $resolver->resolve(
                class: $this->callback,
                options: $options
            );
        }

        return $result;
    }

    /**
     * インスタンスは生成済みか？
     *
     * @return boolean
     */
    public function isBuilded(): bool
    {
        return !is_null($this->instance);
    }

    /**
     * インスタンスの更新
     *
     * @param mixed $instance
     * @return void
     * @throws Error
     */
    public function update($instance): void
    {
        if ($this->instance) {
            if (!($instance instanceof $this->instance)) {
                throw new Error('error: instance type error!');
            }

            $this->instance = $instance;
        }
    }
}
