<?php

namespace Takemo101\Egg\Http;

use Takemo101\Egg\Support\Injector\ContainerContract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\Filter\RouteActionFilter;
use Takemo101\Egg\Http\Invoker\RouteActionInvoker;
use Takemo101\Egg\Http\Resolver\ResponseResolvers;
use Takemo101\Egg\Support\Shared\Handler;
use Throwable;

/**
 * Httpディスパッチャの基本的な実装
 */
final class HttpDispatcher implements HttpDispatcherContract
{
    /**
     * constructor
     *
     * @param RootFilters $filters
     * @param HttpErrorHandlerContract $errorHandler
     * @param RouteActionInvoker $invoker
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly RootFilters $filters,
        private readonly HttpErrorHandlerContract $errorHandler,
        private readonly RouteActionInvoker $invoker,
        private readonly ContainerContract $container,
    ) {
        //
    }

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
    ): Response {
        $this->register(
            request: $request,
            response: $response,
        );

        try {
            // ルートフィルターからルーティングの実行までの処理を実行する
            $response = $this->invoker->invoke(
                request: $request,
                response: $response,
                action: new Handler(fn (Response $response) => $response),
                filters: $this->filters->filters()->add(
                    // このフィルタを追加しないとルーティングが実行されない
                    new Handler(RouteActionFilter::class),
                ),
            );
        } catch (Throwable $e) {
            // エラーハンドリングをする
            $response = $this->error(
                request: $request,
                error: $e,
            );
        }

        return $response;
    }

    /**
     * エラーハンドリングをする
     *
     * @param Request $request
     * @param Throwable $error
     * @return Response
     */
    private function error(
        Request $request,
        Throwable $error,
    ): Response {
        $response = $this->errorHandler->handle(
            request: $request,
            error: $error,
        );

        return $response;
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
        $this->container
            ->alias(
                Request::class,
                'request',
            )
            ->instance(
                Request::class,
                $request,
            );

        $this->container
            ->alias(
                Response::class,
                'response',
            )
            ->instance(
                Response::class,
                $response,
            );
    }
}
