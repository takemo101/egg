<?php

namespace Takemo101\Egg\Kernel;

use Takemo101\Egg\Kernel\Loader\ConfigLoader;
use Takemo101\Egg\Kernel\Loader\EnvironmentLoader;
use Takemo101\Egg\Kernel\Loader\ErrorLoader;
use Takemo101\Egg\Kernel\Loader\FunctionLoader;
use Takemo101\Egg\Kernel\Loader\HelperLoader;
use Takemo101\Egg\Kernel\Loader\HookLoader;
use Takemo101\Egg\Kernel\Loader\LogLoader;
use Takemo101\Egg\Kernel\Loader\ModuleLoader;
use Takemo101\Egg\Kernel\Loader\RoutingLoader;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Filesystem\LocalSystem;
use Takemo101\Egg\Support\Filesystem\LocalSystemContract;
use Takemo101\Egg\Support\Filesystem\PathHelper;
use Takemo101\Egg\Support\Injector\Container;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\ServiceLocator;
use BadMethodCallException;
use Closure;

/**
 * application
 *
 * @method ContainerContract alias(string $class, string $alias)
 * @method ContainerContract instance(string $label, mixed $instance)
 * @method ContainerContract singleton(string $label, Closure|string|null $callback = null)
 * @method ContainerContract bind(string $label, Closure|string|null $callback = null)
 * @method boolean has(string $label)
 * @method void clear()
 * @method mixed make(string $label, mixed[] $options = [])
 * @method mixed call(callable $callback, mixed[] $options = [])
 * @see ContainerContract
 */
final class Application
{
    /**
     * @var string
     */
    public const Name = 'Egg';

    /**
     * @var string
     */
    public const Version = '0.1.0';

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
     * @param ApplicationPath $path
     */
    public function __construct(
        public readonly ApplicationPath $path,
    ) {
        $this->container = new Container();

        $this->bootstrap = new Bootstrap(
            new LoaderResolver($this->container),
        );

        $this->bootstrap->addLoader(
            EnvironmentLoader::class,
            ErrorLoader::class,
            HookLoader::class,
            HelperLoader::class,
            ConfigLoader::class,
            LogLoader::class,
            FunctionLoader::class,
            RoutingLoader::class,
            ModuleLoader::class,
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
        ServiceLocator::init();

        ServiceLocator::set('app', $this);
        ServiceLocator::set('container', $this->container);

        $this->container->instance(
            ContainerContract::class,
            $this->container,
        );

        $this->container->instance(
            Application::class,
            $this,
        );

        $this->container->instance(
            ApplicationPath::class,
            $this->path,
        );

        $this->container->singleton(
            ApplicationEnvironment::class,
            function (ContainerContract $container): ApplicationEnvironment {
                /** @var ConfigRepositoryContract */
                $config = $container->make(ConfigRepositoryContract::class);

                /** @var string */
                $environment = $config->get('app.env', 'local');
                /** @var boolean */
                $debug = $config->get('app.debug', true);

                return new ApplicationEnvironment(
                    environment: $environment,
                    debug: $debug,
                );
            },
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
     * 環境情報を返す
     *
     * @return ApplicationEnvironment
     */
    public function env(): ApplicationEnvironment
    {
        /** @var ApplicationEnvironment */
        $environment = $this->container->make(ApplicationEnvironment::class);

        return $environment;
    }

    /**
     * アプリケーションのパス設定を返す
     *
     * @return ApplicationPath
     */
    public function path(): ApplicationPath
    {
        return $this->path;
    }

    /**
     * コンテナを返す
     *
     * @return ContainerContract
     */
    public function container(): ContainerContract
    {
        return $this->container;
    }

    /**
     * アプリケーションが実行済みか？
     *
     * @return boolean
     */
    public function isBooted(): bool
    {
        return $this->isBooted;
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

    /**
     * コンテナからメソッドを呼び出す
     *
     * @param string $name
     * @param mixed[] $arguments
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->container, $name)) {
            return call_user_func_array(
                [$this->container, $name],
                $arguments,
            );
        }

        throw new BadMethodCallException("method not found: {$name}");
    }
}
