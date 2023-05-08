<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Console\Commands;
use Takemo101\Egg\Console\ConsoleDispatcher;
use Takemo101\Egg\Console\ConsoleDispatcherContract;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Symfony\Component\Console\Application as SymphonyConsoleApplication;
use Takemo101\Egg\Console\CommandResolver;

/**
 * Console関連
 */
final class ConsoleLoader implements LoaderContract
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
        $this->app->bind(
            Commands::class,
            fn () => Commands::empty(),
        );

        $this->app->bind(
            SymphonyConsoleApplication::class,
            function () {
                $application = new SymphonyConsoleApplication(
                    Application::Name,
                    Application::Version,
                );

                $application->setAutoExit(false);

                return $application;
            },
        );

        $this->app->bind(
            ConsoleDispatcherContract::class,
            fn () => new ConsoleDispatcher(
                application: $this->app->make(SymphonyConsoleApplication::class),
                commands: $this->app->make(Commands::class),
                resolver: new CommandResolver($this->app->container),
            ),
        );
    }
}
