<?php

namespace Takemo101\Egg\Http\Invoker;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\Resolver\ResponseResolvers;
use Takemo101\Egg\Routing\Shared\Filters;
use Takemo101\Egg\Support\Shared\Handler;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Shared\CallableCreator;

/**
 * 対象ルートのアクションを実行する
 */
final class RouteActionInvoker
{
    /**
     * @var CallableCreator
     */
    private readonly CallableCreator $creator;

    /**
     * constructor
     *
     * @param ContainerContract $container
     * @param ResponseResolvers $resolvers
     */
    public function __construct(
        private readonly ContainerContract $container,
        private readonly ResponseResolvers $resolvers,
    ) {
        $this->creator = new CallableCreator($container);
    }

    /**
     * アクションの実行
     *
     * @param Request $request
     * @param Response $response
     * @param Handler $action
     * @param Filters $filters
     * @param mixed[] $parameters
     * @return Response
     */
    public function invoke(
        Request $request,
        Response $response,
        Handler $action,
        Filters $filters,
        array $parameters = [],
    ): Response {
        $next = $this->createActionClosure(
            action: $action,
            parameters: $parameters,
        );

        $next = $this->createFiltersClosure(
            next: $next,
            filters: $filters,
        );

        return $this->orResponse(
            response: $response,
            result: $next($request, $response),
        );
    }

    /**
     * アクションのクロージャーを作成する
     *
     * @param Handler $action
     * @param mixed[] $parameters
     * @return Closure
     */
    private function createActionClosure(
        Handler $action,
        array $parameters,
    ): Closure {
        return function (Request $request, Response $response) use ($action, $parameters): Response {
            $result = $this->container->call(
                $this->creator->create($action),
                [
                    'request' => $request,
                    'response' => $response,
                    ...$parameters,
                ],
            );

            return $this->orResponse(
                response: $response,
                result: $this->resolvers->resolve(
                    request: $request,
                    response: $response,
                    result: $result,
                ),
            );
        };
    }

    /**
     * フィルターのクロージャーを作成する
     *
     * @param Closure $next
     * @param Filters $filters
     * @return Closure
     */
    private function createFiltersClosure(
        Closure $next,
        Filters $filters,
    ): Closure {
        // フィルターは追加した順に並んでいるので
        // 逆順で実行する
        $reverses = array_reverse($filters->filters);

        foreach ($reverses as $filter) {
            $next = function (Request $request, Response $response) use ($filter, $next): Response {
                /** @var mixed */
                $result = call_user_func_array(
                    $this->creator->create($filter),
                    [
                        $request,
                        $response,
                        $next,
                    ],
                );

                return $this->orResponse(
                    response: $response,
                    result: $result,
                );
            };
        }

        return $next;
    }


    /**
     * コールバックからの出力結果を比較して
     * レスポンスでない場合は、通常のレスポンスを返す
     *
     * @param Response $response
     * @param mixed $result
     * @return Response
     */
    protected function orResponse(
        Response $response,
        mixed $result,
    ): Response {
        return $result && ($result instanceof Response)
            ? $result
            : $response;
    }
}
