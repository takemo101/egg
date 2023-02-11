<?php

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\Exception\NotFoundHttpException;
use Takemo101\Egg\Http\Filter\CsrfFilter;
use Takemo101\Egg\Routing\RouteBuilder;
use Takemo101\Egg\Support\Log\Loggers;

return function (RouteBuilder $r) {
    $r->get('/', function (Request $request, Response $response, CsrfFilter $csrf) {
        return $response->setContent('
            <form action="/" method="POST">
                <input type="hidden" name="' . CsrfFilter::TokenKey . '" value="' . $csrf->token() .  '">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="name" value="a">
                <input type="submit" value="put">
            </form>
        ');
    })
        ->name('home');

    $r->get('/test', function (Request $request, Response $response) {
        return $response->setContent('test');
    })
        ->name('test');

    $r->put('/', function (Request $request, Response $response) {
        return $response->setContent('put-home');
    })
        ->name('home.edit');

    $r->get('/error', fn () => throw new NotFoundHttpException())
        ->name('error');

    $r->get('/log', function (Loggers $loggers) {
        $loggers->get('app')->info('test');
    })
        ->name('log');

    $r->group(function (RouteBuilder $r) {
        $r->get('/', function (Request $request, Response $response) {
            return new JsonResponse([
                'a' => 'b',
            ]);
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
