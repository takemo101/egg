<?php

namespace Takemo101\Egg\Kernel\Loader;

use Monolog\Level;
use Psr\Log\LoggerInterface;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\ApplicationPath;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Log\FileLoggerFactory;
use Takemo101\Egg\Support\Log\LoggerFactoryContract;
use Takemo101\Egg\Support\Log\Loggers;
use Takemo101\Egg\Support\ServiceLocator;

/**
 * ログ関連
 */
final class LogLoader implements LoaderContract
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
            Loggers::class,
            function (ContainerContract $container): Loggers {
                /** @var ConfigRepositoryContract */
                $config = $container->make(ConfigRepositoryContract::class);

                /** @var ApplicationPath */
                $applicationPath = $container->make(ApplicationPath::class);

                /** @var array<string,string> */
                $filenames = $config->get('log.filename', []);

                /** @var array<string,LoggerFactoryContract> */
                $factories = [];

                /** @var string */
                $defaultKey = $config->get('log.default', 'app');
                /** @var string */
                $path = $config->get('log.path', 'log');
                /** @var Level */
                $level = $config->get('log.level', Level::Debug);

                foreach ($filenames as $key => $filename) {
                    $factories[$key] = new FileLoggerFactory(
                        path: $path,
                        filename: $filename,
                        level: $level,
                        applicationPath: $applicationPath,
                    );
                }

                return new Loggers(
                    factories: $factories,
                    defaultKey: $defaultKey,
                );
            }
        );

        $this->app->singleton(
            LoggerInterface::class,
            function (ContainerContract $container) {

                /** @var Loggers */
                $loggers =  $container->make(Loggers::class);

                return $loggers->default();
            }
        );

        ServiceLocator::factory(
            'logger',
            fn () => $this->app->make(LoggerInterface::class),
        );
    }
}
