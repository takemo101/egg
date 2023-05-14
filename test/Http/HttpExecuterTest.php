<?php

namespace Test\Http;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Takemo101\Egg\Http\Filter\CsrfFilter;
use Takemo101\Egg\Http\Testing\HttpExecuter;
use Test\AppTestCase;

/**
 * http test
 */
class HttpExecuterTest extends AppTestCase
{
    /**
     * @test
     */
    public function getリクエストを実行する__OK()
    {
        /** @var HttpExecuter */
        $executer = $this->app->make(HttpExecuter::class);

        $response = $executer->get(route('home'));

        $this->assertEquals(200, $response->getStatusCode(), 'ステータスコードが200で正常');
    }

    /**
     * @test
     */
    public function getのjsonリクエストを実行する__OK()
    {
        /** @var HttpExecuter */
        $executer = $this->app->make(HttpExecuter::class);

        $response = $executer->getJson(route('home'));

        $this->assertEquals(200, $response->getStatusCode(), 'ステータスコードが200で正常');
    }

    /**
     * @test
     */
    public function putリクエストを実行する__NG()
    {
        /** @var HttpExecuter */
        $executer = $this->app->make(HttpExecuter::class);

        $response = $executer->put(route('home.edit'));

        $this->assertEquals(419, $response->getStatusCode(), 'ステータスコードが419で不正リクエスト');
    }

    /**
     * @test
     */
    public function putリクエストを実行する__OK()
    {
        /** @var HttpExecuter */
        $executer = $this->app->make(HttpExecuter::class);

        $this->app->singleton(Session::class, function () {
            $session = new Session(
                new MockArraySessionStorage(),
            );

            $session->start();

            return $session;
        });

        /** @var CsrfFilter */
        $csrf = $this->app->make(CsrfFilter::class);

        $response = $executer->put(
            route('home.edit'),
            [
                $csrf->key() => $csrf->token(),
            ],
        );

        $this->assertEquals(200, $response->getStatusCode(), 'ステータスコードが200で正常');
    }
}
