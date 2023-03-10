<?php

namespace Takemo101\Egg\Support\Injector;

use ReflectionParameter;
use Takemo101\Egg\Support\Injector\ArgumentResolverContract;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Injector\Resolver\ArgumentNameResolver;
use Takemo101\Egg\Support\Injector\Resolver\DefaultResolver;

/**
 * 引数の解決処理コレクション
 */
final class ArgumentResolvers
{
    /**
     * argument resolver
     *
     * @var ArgumentResolverContract[]
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
     * @param mixed[] $options
     * @return mixed[]
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

    /**
     * デフォルトの引数解決処理を返す
     *
     * @return self
     */
    public static function default(): self
    {
        return new self(
            new DefaultResolver(),
            new ArgumentNameResolver(),
        );
    }
}
