<?php

namespace Takemo101\Egg\Support\Injector;

use InvalidArgumentException;
use Error;
use ReflectionClass;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * オブジェクト生成を解決する
 */
final class ObjectResolver
{
    /**
     * construct
     *
     * @param ContainerContract $container
     * @param ArgumentResolvers $resolvers
     */
    public function __construct(
        private readonly ContainerContract $container,
        private readonly ArgumentResolvers $resolvers,
    ) {
        //
    }

    /**
     * クラス名をチェック
     *
     * @param string $class
     * @throws InvalidArgumentException
     * @return void
     */
    private function checkClassName(string $class)
    {
        if (!class_exists($class)) {
            if (!interface_exists($class)) {
                throw new InvalidArgumentException("error: [{$class}] class does not exist!");
            }
        }
    }

    /**
     * コンテナを参照する
     *
     * @return ContainerContract
     */
    public function container(): ContainerContract
    {
        return $this->container;
    }

    /**
     * オブジェクト生成を解決
     *
     * @param string $class
     * @param array<string,mixed> $options
     * @throws Error|InvalidArgumentException
     * @return object
     */
    public function resolve(
        string $class,
        array $options = [],
    ): object {
        $this->checkClassName($class);

        $reflector = new ReflectionClass($class);

        // インスタンス化できなければ例外
        if (!$reflector->isInstantiable()) {
            throw new Error('error: resolve instance error!');
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new ($class);
        }

        $parameters = $constructor->getParameters();

        // コンストラクタの引数をリフレクションパラメータから解決する
        $arguments = $this->resolvers->resolve(
            container: $this->container,
            parameters: $parameters,
            options: $options
        );

        return $reflector->newInstanceArgs($arguments);
    }
}
