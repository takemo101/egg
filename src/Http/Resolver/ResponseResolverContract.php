<?php

namespace Takemo101\Egg\Http\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * レスポンスデータの解決
 */
interface ResponseResolverContract
{
    /**
     * 受け取った結果をレスポンスに変換する
     * 必ずResponse型でなくてもよくて
     * 最終的にResponse型になってなければ
     * 元々のレスポンスを返す
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $result
     * @return mixed
     */
    public function resolve(
        Request $request,
        Response $response,
        mixed $result,
    ): mixed;
}
