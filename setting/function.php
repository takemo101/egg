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
    Loggers::class,
    fn (Loggers $loggers): Loggers => $loggers,
);

$hook->register(
    RouteBuilder::class,
    function (RouteBuilder $r) {
        $r->get('/hook', function (Response $response) {
            return $response->setContent('hook');
        })
            ->name('hook');

        return $r;
    },
);
