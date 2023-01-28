<?php

define('APP_START_TIME', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

// アプリケーションを起動する
$app = new Takemo101\Egg\Kernel\Application(
    pathSetting: new Takemo101\Egg\Kernel\ApplicationPath(
        basePath: $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__),
    ),
);

$app->addLoader(
    Takemo101\Egg\Kernel\Loader\HttpLoader::class,
);

$app->boot();

// リクエストを処理をする
/** @var Takemo101\Egg\Http\HttpSimpleProcess */
$process = $app->container->make(Takemo101\Egg\Http\HttpSimpleProcess::class);
$process->process();
