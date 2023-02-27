<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Config\ConfigRepository;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Filesystem\LocalSystem;

/**
 * コンフィグ関連
 */
final class ConfigLoader implements LoaderContract
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
        /** @var LocalSystem */
        $filesystem = $this->app->container->make(LocalSystem::class);

        $repository = new ConfigRepository(
            filesystem: $filesystem,
            directory: $this->app->pathSetting->configPath(),
        );

        $this->app->container
            ->alias(ConfigRepositoryContract::class, ConfigRepository::class)
            ->instance(
                ConfigRepositoryContract::class,
                $repository,
            );
    }
}
