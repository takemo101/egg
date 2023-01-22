<?php

namespace Takemo101\Egg\Http;

use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\Exception\NotFoundHttpException;
use Takemo101\Egg\Http\Invoker\RouteActionInvoker;
use Takemo101\Egg\Routing\RouteMatchResult;
use Throwable;

/**
 * Httpディスパッチャの基本的な実装
 */
final class HttpDispatcher implements HttpDispatcherContract
{
    /**
     * @var RouteActionInvoker
     */
    private readonly RouteActionInvoker $invoker;

    /**
     * constructor
     *
     * @param RouterContract $router
     * @param ResponseSenderContract $sender
     * @param RootFilters $rootFilters
     * @param HttpErrorHandlerContract $errorHandler
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly RouterContract $router,
        private readonly ResponseSenderContract $sender,
        private readonly RootFilters $rootFilters,
        private readonly HttpErrorHandlerContract $errorHandler,
        private readonly ContainerContract $container,
    ) {
        $this->invoker = new RouteActionInvoker($container);
    }

    /**
     * http dispatch
     *
     * @param Request $request
     * @param Response $response
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

        try {
            // ルートに一致するものがなければ404
            if (!$result) {
                throw new NotFoundHttpException(
                    message: 'Route Not Found',
                );
            }

            // 一致したルートの処理を実行する
            $this->action(
                request: $request,
                response: $response,
                result: $result,
            );
        } catch (Throwable $e) {
            // エラーハンドリングをする
            $this->error(
                request: $request,
                error: $e,
            );
        }
    }

    /**
     * 一致したルートの処理を実行する
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
            action: $result->action->handler,
            filters: $this->rootFilters->createHttpFilters(
                $result->action->filters,
            ),
            parameters: $result->parameters,
        );

        $this->sender->send($response);
    }

    /**
     * エラーハンドリングをする
     *
     * @param Request $request
     * @param Throwable $error
     * @return void
     */
    private function error(
        Request $request,
        Throwable $error,
    ): void {
        $response = $this->errorHandler->handle(
            request: $request,
            error: $error,
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
