<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Http\ResponseSender;
use Takemo101\Egg\Http\ResponseSenderContract;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Config\ConfigRepository;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Filesystem\LocalSystem;

/**
 * Http関連
 */
final class HttpLoader implements LoaderContract
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
        $this->app->container->bind(
            ResponseSenderContract::class,
            fn () => new ResponseSender(),
        );
    }
}
