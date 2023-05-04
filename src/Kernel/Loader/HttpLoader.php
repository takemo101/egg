<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Http\ErrorHandler\HttpErrorHandler;
use Takemo101\Egg\Http\HttpDispatcher;
use Takemo101\Egg\Http\HttpDispatcherContract;
use Takemo101\Egg\Http\HttpErrorHandlerContract;
use Takemo101\Egg\Http\Resolver\ArrayResponseResolver;
use Takemo101\Egg\Http\Resolver\ResponseResolvers;
use Takemo101\Egg\Http\Resolver\StringResponseResolver;
use Takemo101\Egg\Http\ResponseResponder;
use Takemo101\Egg\Http\ResponseResponderContract;
use Takemo101\Egg\Http\RootFilters;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Shared\CallObject;

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
        $filters = new RootFilters();

        /** @var object */
        $filter = require $this->app
            ->pathSetting
            ->settingPath('filter.php');

        (new CallObject($filter))->bootAndCall(
            $this->app->container,
            $filters,
        );

        $this->app->container->singleton(
            ResponseResolvers::class,
            fn () => new ResponseResolvers(
                new ArrayResponseResolver(),
                new StringResponseResolver(),
            ),
        );

        $this->app->container->bind(
            RootFilters::class,
            fn () => $filters,
        );

        $this->app->container->bind(
            ResponseResponderContract::class,
            fn () => new ResponseResponder(),
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
