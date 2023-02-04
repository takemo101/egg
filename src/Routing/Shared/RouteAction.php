<?php

namespace Takemo101\Egg\Routing\Shared;

use Takemo101\Egg\Support\Shared\Handler;

/**
 * @immutable
 */
final class RouteAction
{
    /**
     * constructor
     *
     * @param Handler $handler
     * @param Filters $filters
     */
    public function __construct(
        public readonly Handler $handler,
        public readonly Filters $filters,
    ) {
        //
    }
}
