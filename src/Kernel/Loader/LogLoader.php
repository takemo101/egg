<?php

namespace Takemo101\Egg\Kernel\Loader;

use Monolog\Level;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\ApplicationPath;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Config\ConfigRepository;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Filesystem\LocalSystem;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Log\FileLoggerFactory;
use Takemo101\Egg\Support\Log\LoggerContract;
use Takemo101\Egg\Support\Log\LoggerFactoryContract;
use Takemo101\Egg\Support\Log\Loggers;

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
        $this->app->container->singleton(
            Loggers::class,
            function (ContainerContract $container): Loggers {
                /** @var ConfigRepositoryContract */
                $config = $container->make(ConfigRepositoryContract::class);

                /** @var ApplicationPath */
                $applicationPath = $container->make(ApplicationPath::class);

                /** @var array<string,string> */
                $filenames = $config->get('log.filenames', []);

                /** @var array<string,LoggerFactoryContract> */
                $factories = [];

                foreach ($filenames as $key => $filename) {
                    $factories[$key] = new FileLoggerFactory(
                        path: $config->get('log.path', 'log'),
                        filename: $filename,
                        level: $config->get('log.level', Level::Debug),
                        applicationPath: $applicationPath,
                    );
                }

                return new Loggers($factories);
            }
        );
    }
}
