<?php

namespace Takemo101\Egg\Support\Injector;

use InvalidArgumentException;
use Error;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionFunctionAbstract;
use Closure;

/**
 * メソッドや関数の呼び出しを解決する
 */
final class CallableResolver
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
     * コンテナを参照する
     *
     * @return ContainerContract
     */
    public function container(): ContainerContract
    {
        return $this->container;
    }

    /**
     * 呼び出しを解決
     *
     * @param callable $callable
     * @param mixed[] $options
     * @throws Error|InvalidArgumentException
     * @return mixed
     */
    public function resolve(
        callable $callable,
        array $options = [],
    ) {
        $reflector = $this->createCallableReflection($callable);

        $parameters = $reflector->getParameters();

        $arguments = $this->resolvers->resolve(
            container: $this->container,
            parameters: $parameters,
            options: $options,
        );

        return call_user_func_array($callable, $arguments);
    }

    /**
     * callableからリフレクションのインスタンスを生成する
     *
     * @param callable $callable
     * @throws Error
     * @return ReflectionFunctionAbstract
     */
    public function createCallableReflection(callable $callable): ReflectionFunctionAbstract
    {
        // for closure
        if ($callable instanceof Closure) {
            return new ReflectionFunction($callable);
        }

        // for callable array
        if (is_array($callable)) {
            [$class, $method] = $callable;

            if (!method_exists($class, $method)) {
                throw new Error('error: method does not exist!');
            }

            return new ReflectionMethod($class, $method);
        }

        // for callable object
        if (is_object($callable) && method_exists($callable, '__invoke')) {
            return new ReflectionMethod($callable, '__invoke');
        }

        // for function
        if (is_string($callable) && function_exists($callable)) {
            return new ReflectionFunction($callable);
        }

        throw new Error('error: resolve callable error!');
    }
}
