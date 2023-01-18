<?php

namespace Takemo101\Egg\Support\Injector\Resolver;

use Error;
use Takemo101\Egg\Support\Injector\ArgumentResolverContract;
use Takemo101\Egg\Support\Injector\ContainerContract;
use ReflectionParameter;

final class DefaultResolver implements ArgumentResolverContract
{
    /**
     * リフレクション引数を引数値に変換する
     *
     * @param ContainerContract $container
     * @param ReflectionParameter[] $parameters
     * @param array<string,mixed> $arguments
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws Error
     */
    public function resolve(
        ContainerContract $container,
        array $parameters,
        array $arguments,
        array $options = []
    ): array {

        // パラメータから引数を取得する
        foreach ($parameters as $parameter) {
            // 可変長引数の場合は例外
            if ($parameter->isVariadic()) {
                throw new Error('resolve argument parameter error');
            }

            $type = $parameter->getType();

            if ($type && !$type->isBuiltin()) {
                $name = $type->getName();

                if (!is_null($class = $parameter->getDeclaringClass())) {
                    if ($name === 'self') {
                        $name = $class->getName();
                    } else if ($name === 'parent' && $parent = $class->getParentClass()) {
                        $name = $parent->getName();
                    }
                }

                $arguments[$parameter->getName()] = $container->make($name);
            } else if ($parameter->isDefaultValueAvailable()) {
                $arguments[$parameter->getName()] = $parameter->getDefaultValue();
            }
        }

        return $arguments;
    }
}
