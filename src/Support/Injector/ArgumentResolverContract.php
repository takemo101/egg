<?php

namespace Takemo101\Egg\Support\Injector;

use ReflectionParameter;

/**
 * 引数解決
 */
interface ArgumentResolverContract
{
    /**
     * リフレクション引数を引数値に変換する
     *
     * @param ContainerContract $container
     * @param ReflectionParameter[] $parameters
     * @param mixed[] $arguments
     * @param mixed[] $options
     * @return mixed[]
     */
    public function resolve(
        ContainerContract $container,
        array $parameters,
        array $arguments,
        array $options = []
    ): array;
}
