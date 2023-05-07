<?php

use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Routing\RouteBuilder;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\ServiceLocator;

/** @var Hook */
$hook = ServiceLocator::get('hook');

$hook->addBy(
    function (RouteBuilder $r) {
        $r->get('/phpinfo', function (Response $response) {
            phpinfo();
        })
            ->name('phpinfo');

        return $r;
    },
);

$hook->add(
    'after-response',
    function (Response $response) {
        return $response;
    },
);
