<?php

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Environment;
use Takemo101\Egg\Support\Log\Loggers;
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

if (!function_exists('logger')) {
    /**
     * キーからロガーを取得する
     *
     * @param string $key
     * @return LoggerInterface
     */
    function logger(string $key): LoggerInterface
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var Loggers */
        $loggers = $app->container->make(Loggers::class);

        return $loggers->get($key);
    }
}

if (!function_exists('route')) {
    /**
     * ルート名からURLを取得する
     *
     * @param string $name
     * @param array<string,mixed> $parameter
     * @return string
     */
    function route(string $name, array $parameter = []): string
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var RouterContract */
        $router = $app->container->make(RouterContract::class);

        return $router->route($name, $parameter);
    }
}

if (!function_exists('session')) {
    /**
     * セッションを取得する
     *
     * @return mixed
     */
    function session(): Session
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        return $app->container->make(Session::class);
    }
}
