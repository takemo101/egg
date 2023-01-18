<?php

namespace Takemo101\Egg\Http\Invoker;

use RuntimeException;
use Takemo101\Egg\Routing\Shared\Handler;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * Invokerの抽象クラス
 */
abstract class AbstractInvoker
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
    protected function createCallable(Handler $handler): callable
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

            return $this->container->call($array);
        }

        if ($handler->isString()) {
            $object = $this->container->make($handler->toString());

            return $this->objectToCallable($object);
        }

        throw new RuntimeException('error! invalid handler');
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
            if (method_exists($object, $method)) {
                return [$object, $method];
            }
        }

        throw new RuntimeException('error! invalid handler object');
    }
}
