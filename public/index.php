<?php

define('APP_START_TIME', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

// アプリケーションを起動して
// リクエストを処理をする
Takemo101\Egg\Http\HttpProcess::fromApplication(
    new Takemo101\Egg\Kernel\Application(
        pathSetting: new Takemo101\Egg\Kernel\ApplicationPath(
            basePath: $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__),
        ),
    ),
)->run();
