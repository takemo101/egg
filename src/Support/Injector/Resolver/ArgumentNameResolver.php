<?php

namespace Takemo101\Egg\Support\Injector\Resolver;

use Error;
use Takemo101\Egg\Support\Injector\ArgumentResolverContract;
use Takemo101\Egg\Support\Injector\ContainerContract;
use ReflectionParameter;

final class ArgumentNameResolver implements ArgumentResolverContract
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
            $name = $parameter->getName();

            if (array_key_exists($name, $options)) {
                $arguments[$name] = $options[$name];
            }

            // 可変長引数の場合は例外
            if ($parameter->isVariadic()) {
                throw new Error('error! variadic argument is not supported.');
            }
        }

        // パラメータが存在しているが引数が存在しない場合はオプションから引数を取得する
        if (count($parameters) && count($arguments) === 0) {
            foreach ($parameters as $i => $parameter) {
                if (isset($options[$i])) {
                    $arguments[$name] = $options[$i];
                }
            }
        }

        return $arguments;
    }
}
