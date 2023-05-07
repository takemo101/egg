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
        $this->app->singleton(
            ResponseResolvers::class,
            fn () => new ResponseResolvers(
                new ArrayResponseResolver(),
                new StringResponseResolver(),
            ),
        );

        $this->app->bind(
            RootFilters::class,
            fn () => new RootFilters(),
        );

        $this->app->bind(
            ResponseResponderContract::class,
            fn () => new ResponseResponder(),
        );

        $this->app->bind(
            HttpErrorHandlerContract::class,
            HttpErrorHandler::class,
        );

        $this->app->bind(
            HttpDispatcherContract::class,
            HttpDispatcher::class,
        );
    }
}
