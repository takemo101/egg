<?php

namespace Takemo101\Egg\Support\Shared;

use RuntimeException;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Shared\Functional;

/**
 * callableを作成する
 */
final class CallableCreator
{
    /**
     * constructor
     *
     * @param ContainerContract $container
     */
    public function __construct(
        protected readonly ContainerContract $container,
    ) {
        //
    }

    /**
     * Functionalからコールバックを作成する
     *
     * @param Functional $function
     * @return callable
     * @throws RuntimeException
     */
    public function create(Functional $function): callable
    {
        if ($function->isClosure()) {
            return $function->toClosure();
        }

        if ($function->isObject()) {
            $object = $function->toObject();

            return $this->objectToCallable($object);
        }

        if ($function->isArray()) {
            $array = $function->toArray();

            return $this->checkCallable($array);
        }

        if ($function->isString()) {
            $object = $this->container->make($function->toString());

            if (!is_object($object)) {
                throw new RuntimeException('error! invalid callable string');
            }

            return $this->objectToCallable($object);
        }

        return $this->checkCallable($function->callable);
    }

    /**
     * オブジェクトからコールバックを作成する
     *
     * @param object $object
     * @return callable
     */
    private function objectToCallable(object $object): callable
    {
        if (is_callable($object)) {
            return $object;
        }

        foreach ([
            'handle',
            '__invoke',
        ] as $method) {
            $set = [$object, $method];

            if (method_exists($object, $method)) {
                return $this->checkCallable($set);
            }
        }

        throw new RuntimeException('error! invalid callable object');
    }

    /**
     * callableをチェックして返す
     *
     * @param mixed $callable
     * @return callable
     * @throws RuntimeException
     */
    private function checkCallable(mixed $callable): callable
    {
        if (is_callable($callable)) {
            return $callable;
        }

        throw new RuntimeException('error! invalid callable');
    }
}
