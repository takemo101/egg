<?php

namespace Test\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\HttpDispatcher;
use Test\AppTestCase;

/**
 * http test
 */
class HttpDispatcherTest extends AppTestCase
{
    /**
     * @test
     */
    public function HttpDispatcherを実行する__OK()
    {
        /** @var HttpDispatcher */
        $dispatcher = $this->app->make(HttpDispatcher::class);

        $response = $dispatcher->dispatch(
            request: Request::create('http://localhost/test'),
            response: new Response(),
        );

        $this->assertEquals(200, $response->getStatusCode(), 'ステータスコードが200で正常');
        $this->assertEquals('test', $response->getContent(), 'testルートのコンテンツを取得できる');
    }
}
