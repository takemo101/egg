<?php

namespace Takemo101\Egg\Support\Injector;

use Error;

/**
 * 依存注入するための定義
 */
final class InstanceDefinition implements DefinitionContract
{
    /**
     * construct
     *
     * @param mixed $instance
     */
    public function __construct(
        private mixed $instance,
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
        return $this->instance;
    }

    /**
     * インスタンスは生成済みか？
     *
     * @return boolean
     */
    public function isBuilded(): bool
    {
        return true;
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
        if (!($instance instanceof $this->instance))
            throw new Error('error: instance type error!');

        $this->instance = $instance;
    }
}
