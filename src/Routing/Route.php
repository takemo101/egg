<?php

namespace Takemo101\Egg\Routing;

use Takemo101\Egg\Routing\Shared\Equatable;
use Takemo101\Egg\Routing\Shared\URN;
use Takemo101\Egg\Routing\Shared\HttpMethods;
use Takemo101\Egg\Routing\Shared\Name;
use Takemo101\Egg\Routing\Shared\RouteAction;

final class Route implements Equatable
{
    /**
     * constructor
     *
     * @param URN $urn
     * @param HttpMethods $methods
     * @param RouteAction $action
     * @param Name|null $name
     */
    public function __construct(
        public readonly URN $urn,
        public readonly HttpMethods $methods,
        public readonly RouteAction $action,
        public readonly ?Name $name = null,
    ) {
        //
    }

    /**
     * 等しいか？
     *
     * @param static $other
     * @return boolean
     */
    public function equals(object $other): bool
    {
        return $other instanceof static
            && $this->urn->equals($other->urn);
    }
}
