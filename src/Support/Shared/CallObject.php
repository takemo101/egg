<?php

namespace Takemo101\Egg\Support\Shared;

use RuntimeException;
use Closure;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * オブジェクトを何らかのメソッドで呼び出す
 */
final class CallObject
{
    /**
     * constructor
     *
     * @param object $object
     * @param string $bootMethod
     * @param string[] $handleMethods
     */
    public function __construct(
        private readonly object $object,
        private readonly string $bootMethod = 'boot',
        private readonly array $handleMethods = [
            '__invoke',
            'handle',
        ],
    ) {
        //
    }

    /**
     * オブジェクトのbootメソッドを呼び出す
     *
     * @param ContainerContract $container
     * @return void
     */
    public function boot(ContainerContract $container): void
    {
        $callable = $this->object;

        if (method_exists($callable, $this->bootMethod)) {
            $container->call([$callable, $this->bootMethod]);
        }
    }

    /**
     * オブジェクトのメソッドを呼び出す
     *
     * @param mixed ...$arguments
     * @return mixed
     */
    public function call(mixed ...$arguments): mixed
    {
        $callable = $this->object;

        // Closure
        if ($callable instanceof Closure) {
            return $callable(...$arguments);
        }

        foreach ($this->handleMethods as $method) {
            if (method_exists($callable, $method)) {
                return call_user_func_array(
                    [$callable, $method],
                    $arguments
                );
            }
        }

        throw new RuntimeException('error: not found handle method');
    }

    /**
     * bootしてからオブジェクトのメソッドを呼び出す
     *
     * @param ContainerContract $container
     * @param mixed ...$arguments
     * @return mixed
     */
    public function bootAndCall(
        ContainerContract $container,
        mixed ...$arguments
    ): mixed {
        $this->boot($container);

        return $this->call(...$arguments);
    }
}
