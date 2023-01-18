<?php

define('APP_START_TIME', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

// アプリケーションを起動する
$app = new Takemo101\Egg\Kernel\Application(
    pathSetting: new Takemo101\Egg\Kernel\ApplicationPathSetting(
        basePath: $_ENV['APPLICATION_BASE_PATH'] ?? dirname(__DIR__),
        settingPath: 'setting',
        configPath: 'config',
    ),
);

$app->addLoader(
    Takemo101\Egg\Kernel\Loader\HttpLoader::class,
);

$app->boot();

// ルーティングを処理する
/** @var Takemo101\Egg\Http\HttpDispatcher */
$dispatcher = $app->container->make(Takemo101\Egg\Http\HttpDispatcher::class);
$dispatcher->dispatch(
    request: Symfony\Component\HttpFoundation\Request::createFromGlobals(),
    response: new Symfony\Component\HttpFoundation\Response(),
);
