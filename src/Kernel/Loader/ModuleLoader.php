<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Module\ModuleResolver;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Module\ModuleBooter;
use Takemo101\Egg\Module\Modules;
use Takemo101\Egg\Module\TestModule;
use Takemo101\Egg\Support\ResourcePublisher\PublishResourceModule;
use Takemo101\Egg\Support\Shared\CallObject;

/**
 * モジュール関連
 */
final class ModuleLoader implements LoaderContract
{
    /**
     * @var class-string[]
     */
    private array $modules = [
        PublishResourceModule::class,
    ];

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
        $modules = new Modules(...$this->modules);

        /** @var object */
        $module = require $this->app
            ->pathSetting
            ->settingPath('module.php');

        (new CallObject($module))->bootAndCall(
            $this->app->container,
            $modules,
        );

        $this->app->container->bind(
            Modules::class,
            fn () => $modules,
        );

        /** @var ModuleResolver */
        $resolver = $this->app->container->make(ModuleResolver::class);

        (new ModuleBooter(
            $modules,
            $resolver,
        ))->boot();
    }
}
