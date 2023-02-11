<?php

namespace Takemo101\Egg\Http\Resolver;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 文字列データをレスポンスへ解決
 */
final class StringResponseResolver implements ResponseResolverContract
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
    ): mixed {
        return is_string($result)
            ? $response->setContent($result)
            : $result;
    }
}
