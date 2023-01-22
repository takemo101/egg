<?php

namespace Takemo101\Egg\Http\Invoker;

use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Shared\CallableCreator;

/**
 * Invokerの抽象クラス
 */
abstract class AbstractInvoker
{
    /**
     * @var CallableCreator
     */
    protected readonly CallableCreator $creator;

    /**
     * constructor
     *
     * @param ContainerContract $container
     */
    public function __construct(
        protected readonly ContainerContract $container,
    ) {
        $this->creator = new CallableCreator($container);
    }
}
