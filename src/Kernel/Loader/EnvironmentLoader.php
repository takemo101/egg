<?php

namespace Takemo101\Egg\Kernel\Loader;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\RepositoryInterface;
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
        $repository = RepositoryBuilder::createWithDefaultAdapters()
            ->addAdapter(PutenvAdapter::class)
            ->immutable()
            ->make();

        Dotenv::create(
            repository: $repository,
            paths: $this->app->pathSetting->basePath(),
            names: $this->app->pathSetting->dotenv,
        )
            ->load();

        $this->app->container->instance(
            RepositoryInterface::class,
            $repository,
        );

        $this->app->container->instance(
            Environment::class,
            new Environment($repository),
        );
    }
}
