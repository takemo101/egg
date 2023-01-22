<?php

namespace Takemo101\Egg\Support\Injector;

use Closure;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * 依存注入するための定義
 */
interface DefinitionContract
{
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
    );

    /**
     * インスタンスは生成済みか？
     *
     * @return boolean
     */
    public function isBuilded(): bool;

    /**
     * インスタンスの更新
     *
     * @param mixed $instance
     * @return void
     */
    public function update($instance): void;
}
