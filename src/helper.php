<?php

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Environment;
use Takemo101\Egg\Support\StaticContainer;

if (!function_exists('env')) {
    /**
     * 環境変数を取得する
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var Environment */
        $environment = $app->container->make(Environment::class);

        return $environment->get($key, $default);
    }
}

if (!function_exists('config')) {
    /**
     * コンフィグを取得する
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var Environment */
        $config = $app->container->make(ConfigRepositoryContract::class);

        return $config->get($key, $default);
    }
}
