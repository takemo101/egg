<?php

use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Routing\RouteBuilder;
use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\Log\Loggers;
use Takemo101\Egg\Support\StaticContainer;

/** @var Hook */
$hook = StaticContainer::get('hook');

$hook->register(
    RouteBuilder::class,
    function (RouteBuilder $r) {
        $r->get('/phpinfo', function (Response $response) {
            phpinfo();
        })
            ->name('phpinfo');

        return $r;
    },
);

$hook->register(
    'after-response',
    function (Response $response) {
        return $response;
    },
);
