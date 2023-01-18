<?php

namespace Takemo101\Egg\Http;

use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\Invoker\RouteActionInvoker;
use Takemo101\Egg\Routing\RouteMatchResult;

/**
 * Webアプリケーションのディスパッチャ
 * ルーティングの結果を元にアクションを呼び出す
 */
final class HttpDispatcher
{
    /**
     * constructor
     *
     * @param RouterContract $router
     * @param RouteActionInvoker $invoker
     * @param ResponseSenderContract $sender
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly RouterContract $router,
        private readonly RouteActionInvoker $invoker,
        private readonly ResponseSenderContract $sender,
        private readonly ContainerContract $container,
    ) {
        //
    }

    /**
     * http dispatch
     *
     * @param Request $request
     * @param
     * @return void
     */
    public function dispatch(
        Request $request,
        Response $response,
    ): void {
        $result = $this->router->match(
            uri: $request->getUri(),
            method: $request->getMethod(),
        );

        $this->register(
            request: $request,
            response: $response,
        );

        if ($result) {
            $this->action(
                request: $request,
                response: $response,
                result: $result,
            );
        }
    }

    /**
     * ルートに一致した処理を実行する
     *
     * @param Request $request
     * @param Response $response
     * @param RouteMatchResult $result
     * @return void
     */
    private function action(
        Request $request,
        Response $response,
        RouteMatchResult $result,
    ): void {
        $response = $this->invoker->invoke(
            request: $request,
            response: $response,
            action: $result->action,
            parameters: $result->parameters,
        );

        $this->sender->send($response);
    }

    /**
     * リクエストなどに関連する依存関係を登録する
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    private function register(
        Request $request,
        Response $response,
    ): void {
        $this->container->instance(
            Request::class,
            $request,
        );
        $this->container->alias(
            Request::class,
            'request',
        );

        $this->container->instance(
            Response::class,
            $response,
        );
        $this->container->alias(
            Response::class,
            'response',
        );
    }
}
