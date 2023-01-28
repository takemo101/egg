<?php

namespace Takemo101\Egg\Kernel\Loader;

use Dotenv\Dotenv;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Environment;

/**
 * 環境変数関連
 */
final class EnvironmentLoader implements LoaderContract
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
        $data = Dotenv::createImmutable(
            $this->app->pathSetting->basePath(),
            $this->app->pathSetting->dotenv,
        )
            ->load();

        $this->app->container->instance(
            Environment::class,
            new Environment($data),
        );
    }
}
