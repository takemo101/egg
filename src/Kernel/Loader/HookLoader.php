<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\Hook\HookDefinitionDataFilter;
use Takemo101\Egg\Support\Injector\DefinitionDataFilters;
use Takemo101\Egg\Support\Shared\CallableCreator;
use Takemo101\Egg\Support\ServiceLocator;

/**
 * フック処理関連
 */
final class HookLoader implements LoaderContract
{
    /**
     * constructor
     *
     * @param Application $app
     */
    public function __construct(
        private readonly Application $app,
    ) {
        //
    }

    /**
     * ロード処理をする
     *
     * @return void
     */
    public function load(): void
    {
        $hook = new Hook(new CallableCreator($this->app->container));

        $this->app->container->instance(Hook::class, $hook);

        ServiceLocator::set('hook', $hook);

        /** @var DefinitionDataFilters */
        $filters = $this->app->container->make(DefinitionDataFilters::class);

        $filters->add(
            new HookDefinitionDataFilter($hook),
        );
    }
}
