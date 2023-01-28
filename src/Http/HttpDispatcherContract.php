<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * WebアプリケーションのHttpディスパッチャ
 * ルーティングの結果を元にアクションを呼び出す
 */
interface HttpDispatcherContract
{
    /**
     * http dispatch
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function dispatch(
        Request $request,
        Response $response,
    ): Response;
}
