<?php

namespace Takemo101\Egg\Http\Filter;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\Exception\NotFoundHttpException;
use Takemo101\Egg\Http\Invoker\RouteActionInvoker;
use Takemo101\Egg\Http\Resolver\ResponseResolvers;
use Takemo101\Egg\Routing\RouteMatchResult;
use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * ルーティングによってアクションを実行するためのフィルタ
 * HttpDispatcherのフィルタ処理で必ず使用する
 */
class RouteActionFilter
{
    /**
     * constructor
     *
     * @param RouteActionInvoker $invoker
     * @param RouterContract $router
     * @param Hook $hook
     */
    public function __construct(
        private readonly RouteActionInvoker $invoker,
        private readonly RouterContract $router,
        private readonly Hook $hook,
    ) {
        //
    }

    /**
     * アクションを実行する
     *
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Response $response, Closure $next): Response
    {
        $result = $this->router->match(
            uri: $request->getUri(),
            method: $request->getMethod(),
        );

        // ルートに一致するものがなければ404
        if (!$result) {
            throw new NotFoundHttpException(
                message: 'Route Not Found',
            );
        }

        // 一致したルートの処理を実行する
        $response = $this->action(
            request: $request,
            response: $response,
            result: $result,
        );

        /** @var Request */
        $request = $this->hook->applyFilter(
            'before-request',
            $request,
        );

        $result = $next($request, $response);

        /** @var Response */
        $response = $this->hook->applyFilter(
            'after-response',
            $response,
        );

        return $result;
    }

    /**
     * 一致したルートの処理を実行する
     *
     * @param Request $request
     * @param Response $response
     * @param RouteMatchResult $result
     * @return Response
     */
    private function action(
        Request $request,
        Response $response,
        RouteMatchResult $result,
    ): Response {
        $response = $this->invoker->invoke(
            request: $request,
            response: $response,
            action: $result->action->handler,
            filters: $result->action->filters,
            parameters: $result->parameters,
        );

        return $response;
    }
}
