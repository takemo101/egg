<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Http\ErrorHandler\HttpErrorHandler;
use Takemo101\Egg\Http\HttpDispatcher;
use Takemo101\Egg\Http\HttpDispatcherContract;
use Takemo101\Egg\Http\HttpErrorHandlerContract;
use Takemo101\Egg\Http\ResponseSender;
use Takemo101\Egg\Http\ResponseSenderContract;
use Takemo101\Egg\Http\RootFilters;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;

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
        /** @var mixed[] */
        $filters = require $this->app
            ->pathSetting
            ->settingPath('filter.php');

        $this->app->container->bind(
            RootFilters::class,
            fn () => new RootFilters(...$filters),
        );

        $this->app->container->bind(
            ResponseSenderContract::class,
            fn () => new ResponseSender(),
        );

        $this->app->container->bind(
            HttpErrorHandlerContract::class,
            HttpErrorHandler::class,
        );

        $this->app->container->bind(
            HttpDispatcherContract::class,
            HttpDispatcher::class,
        );
    }
}
