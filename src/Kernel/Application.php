<?php

namespace Takemo101\Egg\Kernel;

use Takemo101\Egg\Kernel\Loader\ConfigLoader;
use Takemo101\Egg\Kernel\Loader\DependencyLoader;
use Takemo101\Egg\Kernel\Loader\EnvironmentLoader;
use Takemo101\Egg\Kernel\Loader\ErrorLoader;
use Takemo101\Egg\Kernel\Loader\HelperLoader;
use Takemo101\Egg\Kernel\Loader\RoutingLoader;
use Takemo101\Egg\Support\Filesystem\LocalSystem;
use Takemo101\Egg\Support\Filesystem\LocalSystemContract;
use Takemo101\Egg\Support\Filesystem\PathHelper;
use Takemo101\Egg\Support\Injector\Container;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * application
 */
final class Application
{
    /**
     * @var ContainerContract
     */
    public readonly ContainerContract $container;

    /**
     * @var Bootstrap
     */
    private readonly Bootstrap $bootstrap;

    /**
     * @var bool
     */
    private bool $isBooted = false;

    /**
     * constructor
     *
     * @param ApplicationPathSetting $pathSetting
     */
    public function __construct(
        public readonly ApplicationPathSetting $pathSetting,
    ) {
        $this->container = new Container();

        $this->bootstrap = new Bootstrap(
            new LoaderResolver($this->container),
        );

        $this->bootstrap->addLoader(
            EnvironmentLoader::class,
            ErrorLoader::class,
            DependencyLoader::class,
            HelperLoader::class,
            ConfigLoader::class,
            RoutingLoader::class,
        );

        $this->register();
    }

    /**
     * 基本的な依存関係を登録する
     *
     * @return void
     */
    private function register(): void
    {
        $this->container->instance(
            ContainerContract::class,
            $this->container,
        );

        $this->container->instance(
            Application::class,
            $this,
        );

        $this->container->instance(
            ApplicationPathSetting::class,
            $this->pathSetting,
        );

        foreach ([
            LocalSystemContract::class => LocalSystem::class,
            PathHelper::class => PathHelper::class,
        ] as $class => $callback) {
            $this->container->bind(
                $class,
                $callback,
            );
        }
    }

    /**
     * ローダーの追加
     *
     * @param string ...$loaders
     * @return self
     */
    public function addLoader(string ...$loaders): self
    {
        $this->bootstrap->addLoader(...$loaders);

        return $this;
    }

    /**
     * アプリケーションを実行する
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->isBooted) {
            return;
        }

        $this->bootstrap->boot();

        $this->isBooted = true;
    }
}
