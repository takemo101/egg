<?php

use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Routing\RouteBuilder;

return function (RouteBuilder $r) {
    $r->get('/', function (Response $response) {
        return $response->setContent('Hello World');
    })
        ->name('home');

    $r->group(function (RouteBuilder $r) {
        $r->get('/', function () {
            echo 'index';
        })
            ->name('index');

        $r->get('/[i:id]', function (int $id) {
            echo $id;
        })
            ->name('show');

        $r->put('/[i:id]/edit', function (int $id) {
            echo $id;
        })
            ->name('edit');
    })
        ->path('group')
        ->name('group.');
};
