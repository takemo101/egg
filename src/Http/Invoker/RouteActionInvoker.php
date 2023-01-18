<?php

namespace Takemo101\Egg\Http\Invoker;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Routing\Shared\RouteAction;

/**
 * 対象ルートのアクションを実行する
 */
final class RouteActionInvoker extends AbstractInvoker
{
    /**
     * アクションの実行
     *
     * @param RouteAction $action
     * @param Request $request
     * @param Response $response
     * @param array $parameters
     * @return Response
     */
    public function invoke(
        Request $request,
        Response $response,
        RouteAction $action,
        array $parameters,
    ): Response {

        $next = function (Request $request, Response $response) use ($action, $parameters): Response {
            $result = $this->container->call(
                $this->createCallable($action->handler),
                [
                    'request' => $request,
                    'response' => $response,
                    ...$parameters,
                ],
            );

            return $this->orResponse($result, $response);
        };

        // フィルターは追加した順に並んでいるので
        // 逆順で実行する
        $reverses = array_reverse($action->filters->filters);

        foreach ($reverses as $filter) {
            $next = function (Request $request, Response $response) use ($filter, $next): Response {
                /** @var mixed */
                $result = call_user_func_array(
                    $this->createCallable($filter),
                    [
                        $request,
                        $response,
                        $next,
                    ],
                );

                return $this->orResponse($result, $response);
            };
        }

        return $this->orResponse($next($request, $response), $response);
    }

    /**
     * コールバックからの出力結果を比較して
     * レスポンスでない場合は、通常のレスポンスを返す
     *
     * @param mixed $result
     * @param Response $response
     * @return Response
     */
    private function orResponse(mixed $result, Response $response): Response
    {
        return $result && ($result instanceof Response)
            ? $result
            : $response;
    }
}
