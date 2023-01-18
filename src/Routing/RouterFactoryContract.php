<?php

namespace Takemo101\Egg\Routing;

/**
 * Routerを生成する
 */
interface RouterFactoryContract
{
    /**
     * RouteBuilderからRouterを生成する
     *
     * @param RouteBuilder $builder
     * @return RouterContract
     */
    public function create(RouteBuilder $builder): RouterContract;
}
