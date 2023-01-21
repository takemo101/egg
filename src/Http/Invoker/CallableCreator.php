<?php

namespace Takemo101\Egg\Http\Invoker;

use RuntimeException;
use Takemo101\Egg\Routing\Shared\Handler;
use Takemo101\Egg\Support\Injector\ContainerContract;

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
     * Handlerからコールバックを作成する
     *
     * @param Handler $handler
     * @return callable
     * @throws RuntimeException
     */
    public function create(Handler $handler): callable
    {
        if ($handler->isClosure()) {
            return $handler->toClosure();
        }

        if ($handler->isObject()) {
            $object = $handler->toObject();

            return $this->objectToCallable($object);
        }

        if ($handler->isArray()) {
            $array = $handler->toArray();

            return $this->checkCallable($array);
        }

        if ($handler->isString()) {
            $object = $this->container->make($handler->toString());

            if (!is_object($object)) throw new RuntimeException('error! invalid handler string');

            return $this->objectToCallable($object);
        }

        return $this->checkCallable($handler->handler);
    }

    /**
     * オブジェクトからコールバックを作成する
     *
     * @param object $object
     * @return callable
     */
    private function objectToCallable(object $object): callable
    {
        if (is_callable($object)) return $object;

        foreach ([
            'handle',
            '__invoke',
        ] as $method) {

            $set = [$object, $method];

            if (method_exists($object, $method)) {
                return $this->checkCallable($set);
            }
        }

        throw new RuntimeException('error! invalid handler object');
    }

    /**
     * callableをチェックして返す
     *
     * @param mixed $collable
     * @return callable
     * @throws RuntimeException
     */
    private function checkCallable(mixed $collable): callable
    {
        if (is_callable($collable)) return $collable;

        throw new RuntimeException('error! invalid handler');
    }
}
