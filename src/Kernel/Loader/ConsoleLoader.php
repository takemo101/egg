<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Console\Commands;
use Takemo101\Egg\Console\ConsoleDispatcher;
use Takemo101\Egg\Console\ConsoleDispatcherContract;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Symfony\Component\Console\Application as SymphonyConsoleApplication;
use Takemo101\Egg\Console\CommandResolver;
use Takemo101\Egg\Support\Shared\CallObject;

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
        $commands = Commands::empty();

        /** @var object */
        $command = require $this->app
            ->path
            ->getSettingPath('command.php');

        (new CallObject($command))->bootAndCall(
            $this->app->container,
            $commands,
        );

        $this->app->container->bind(
            Commands::class,
            fn () => $commands,
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
                commands: $this->app->container->make(Commands::class),
                resolver: new CommandResolver($this->app->container),
            ),
        );
    }
}
