<?php

namespace Takemo101\Egg\Module;

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Support\Hook\Hook;

/**
 * モジュールのベース
 */
abstract class Module implements ModuleContract
{
    /**
     * constructor
     *
     * @param Application $app
     */
    public function __construct(
        protected readonly Application $app,
    ) {
        //
    }

    /**
     * フックを取得する
     *
     * @return Hook
     */
    protected function hook(): Hook
    {
        /** @var */
        $hook = $this->app->container->make(Hook::class);

        return $hook;
    }

    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
