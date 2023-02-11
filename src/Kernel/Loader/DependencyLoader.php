<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Shared\CallObject;

/**
 * 依存関係
 */
final class DependencyLoader implements LoaderContract
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
        /** @var object */
        $dependency = require $this->app
            ->pathSetting
            ->settingPath('dependency.php');

        (new CallObject($dependency))->call($this->app->container);
    }
}
