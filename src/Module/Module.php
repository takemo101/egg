<?php

namespace Takemo101\Egg\Module;

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Module\Default\ResourcePublisher\PublishResources;
use RuntimeException;

/**
 * モジュールのベース
 */
abstract class Module implements ModuleContract
{
    /**
     * constructor
     *
     * @param Application $app
     */
    public function __construct(
        protected readonly Application $app,
    ) {
        //
    }

    /**
     * フックを取得する
     *
     * @return Hook
     */
    protected function hook(): Hook
    {
        /** @var Hook */
        $hook = $this->app->container->make(Hook::class);

        return $hook;
    }

    /**
     * リソース公開設定をする
     *
     * @param string $tag
     * @param array<string,string> $fromTo
     * @return void
     */
    protected function publishes(string $tag, array $fromTo): void
    {
        /** @var PublishResources */
        $resources = $this->app->container->make(PublishResources::class);

        $resources->set($tag, $fromTo);
    }

    /**
     * コンフィグをマージする
     *
     * @param string $key
     * @param string $path
     * @return void
     * @throws RuntimeException
     */
    protected function mergeConfig(string $key, string $path): void
    {
        /** @var ConfigRepositoryContract */
        $config = $this->app->container->make(ConfigRepositoryContract::class);

        // キーに対するコンフィグがある場合はマージする
        if ($config->hasKey($key)) {
            /** @var array<string,mixed> */
            $data = require $path;

            if (!is_array($data)) {
                throw new RuntimeException("Config file must return array. ({$path})");
            }

            $config->set($key, [
                ...$data,
                ...$config->get($key, []),
            ]);
        }
        // キーに対するコンフィグがない場合はパスを設定する
        else {
            $config->setPath($key, $path);
        }
    }

    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
