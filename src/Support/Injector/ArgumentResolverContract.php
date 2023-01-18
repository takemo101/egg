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
     * @param Container $container
     * @param ReflectionParameter[] $parameters
     * @param array $arguments
     * @param array $options
     * @return array
     */
    public function resolve(
        Container $container,
        array $parameters,
        array $arguments,
        array $options = []
    ): array;
}
