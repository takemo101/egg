<?php

namespace Takemo101\Egg\Routing\Shared;

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
