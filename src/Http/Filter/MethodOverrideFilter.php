<?php

namespace Takemo101\Egg\Http\Filter;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * httpメソッドのオーバーライドを有効にする
 */
class MethodOverrideFilter
{
    /**
     * httpメソッドのオーバーライドを有効にする
     *
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Response $response, Closure $next): Response
    {
        $request->enableHttpMethodParameterOverride();

        return $next($request, $response);
    }
}
