<?php

namespace Takemo101\Egg\Support\Shared;

use RuntimeException;
use Takemo101\Egg\Support\Shared\Handler;
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
     * @param string[] $handleMethods
     */
    public function __construct(
        private readonly ContainerContract $container,
        private readonly array $handleMethods = ['handle', '__invoke'],
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

            return $this->arrayToCallable($array);
        }

        if ($function->isString()) {
            $object = $this->container->make($function->toString());

            if (!is_object($object)) {
                throw new RuntimeException('error: invalid callable string!');
            }

            return $this->objectToCallable($object);
        }

        return $this->checkCallable($function->callable);
    }

    /**
     * 配列からコールバックを作成する
     *
     * @param mixed[] $array
     * @return callable
     * @throws RuntimeException
     */
    private function arrayToCallable(array $array): callable
    {
        if (is_callable($array)) {
            return $array;
        }

        $first = $array[0] ?? throw new RuntimeException('error: array is empty!');

        if (is_string($first)) {
            $first = $this->container->make($first);
        }

        if (!is_object($first)) {
            throw new RuntimeException('error: invalid callable array!');
        }

        if ($second = $array[1] ?? false) {
            return $this->checkCallable([$first, $second]);
        }

        return $this->objectToCallable($first);
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

        foreach ($this->handleMethods as $method) {
            $set = [$object, $method];

            if (method_exists($object, $method)) {
                return $this->checkCallable($set);
            }
        }

        throw new RuntimeException('error: invalid callable object!');
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

        throw new RuntimeException('error: invalid callable!');
    }
}
