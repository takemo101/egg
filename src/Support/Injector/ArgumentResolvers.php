<?php

namespace Takemo101\Egg\Support\Injector;

use InvalidArgumentException;
use Error;
use ReflectionClass;
use ReflectionParameter;
use Takemo101\Egg\Support\Injector\ArgumentResolverContract;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * 色々な引数を解決するクラス
 */
final class ArgumentResolvers
{
    /**
     * argument resolver
     *
     * @var ArgumentResolver[]
     */
    private readonly array $resolvers;

    /**
     * construct
     *
     * @param ArgumentResolverContract ...$resolvers
     */
    public function __construct(ArgumentResolverContract ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * 引数をリフレクションパラメータから解決する
     *
     * @param ContainerContract $container
     * @param ReflectionParameter[] $parameters
     * @param array $options
     * @return array
     */
    public function resolve(
        ContainerContract $container,
        array $parameters,
        array $options = []
    ): array {
        $arguments = [];
        foreach ($this->resolvers as $resolver) {
            $arguments = $resolver->resolve(
                container: $container,
                parameters: $parameters,
                arguments: $arguments,
                options: $options
            );
        }

        return $arguments;
    }
}
