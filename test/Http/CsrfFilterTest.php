<?php

namespace Test\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Takemo101\Egg\Http\Exception\CsrfTokenMismatchHttpException;
use Takemo101\Egg\Http\Filter\CsrfFilter;

/**
 * csrf filter test
 */
class CsrfFilterTest extends TestCase
{
    /**
     * @test
     */
    public function Csrfトークンを付与したPostリクエストを実行する__OK()
    {
        $filter = new CsrfFilter(
            new Session(
                new MockArraySessionStorage(),
            )
        );

        $token = $filter->generateToken();


        $request = Request::create(
            uri: 'http://localhost/test',
            method: 'POST',
            parameters: [
                CsrfFilter::TokenKey => $token,
            ],
        );

        $response = new Response();

        // フォームパラメータにトークンを付与してリクエスト
        $filter->handle(
            request: $request,
            response: $response,
            next: function (Request $request, Response $response) {
                return $response;
            }
        );

        $request = Request::create(
            uri: 'http://localhost/test',
            method: 'POST',
            server: [
                CsrfFilter::TokenHeader => $token,
            ],
        );

        // ヘッダーにトークンを付与してリクエスト
        $filter->handle(
            request: $request,
            response: $response,
            next: function (Request $request, Response $response) {
                return $response;
            }
        );

        $this->assertTrue(
            $filter->validateToken($token),
            'トークンのバリデーションが正常に行われる'
        );
    }

    /**
     * @test
     */
    public function Csrfトークンを付与したPostリクエストを実行する__NG()
    {
        $this->expectException(CsrfTokenMismatchHttpException::class);

        $filter = new CsrfFilter(
            new Session(
                new MockArraySessionStorage(),
            )
        );

        $token = $filter->generateToken();


        $request = Request::create(
            uri: 'http://localhost/test',
            method: 'POST',
            parameters: [
                CsrfFilter::TokenKey => 'aaa',
            ],
        );

        $response = new Response();

        $filter->handle(
            request: $request,
            response: $response,
            next: function (Request $request, Response $response) {
                return $response;
            }
        );
    }

    /**
     * @test
     */
    public function Csrfトークンを付与しないGetリクエストを実行する__NG()
    {
        $filter = new CsrfFilter(
            new Session(
                new MockArraySessionStorage(),
            )
        );

        $request = Request::create(
            uri: 'http://localhost/test',
            method: 'GET',
        );

        $response = new Response();

        $response = $filter->handle(
            request: $request,
            response: $response,
            next: function (Request $request, Response $response) {
                return $response;
            }
        );

        $this->assertEquals(
            200,
            $response->getStatusCode(),
            'ステータスコードが200で正常',
        );
    }
}
