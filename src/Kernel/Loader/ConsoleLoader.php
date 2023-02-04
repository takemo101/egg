<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Console\CommandCollection;
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
        /** @var array<object|class-string> */
        $commands = require $this->app
            ->pathSetting
            ->settingPath('command.php');

        $this->app->container->bind(
            CommandCollection::class,
            fn () => new CommandCollection(...$commands),
        );

        $this->app->container->bind(
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

        $this->app->container->bind(
            ConsoleDispatcherContract::class,
            fn () => new ConsoleDispatcher(
                application: $this->app->container->make(SymphonyConsoleApplication::class),
                commands: $this->app->container->make(CommandCollection::class),
                resolver: new CommandResolver($this->app->container),
            ),
        );
    }
}
