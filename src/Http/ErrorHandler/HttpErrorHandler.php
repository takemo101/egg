<?php

namespace Takemo101\Egg\Http\ErrorHandler;

use Takemo101\Egg\Http\HttpErrorHandlerContract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\Exception\HttpException;
use Takemo101\Egg\Http\Exception\InternalServerErrorHttpException;
use Takemo101\Egg\Kernel\ApplicationEnvironment;
use Takemo101\Egg\Support\Log\Loggers;
use Throwable;

/**
 * 基本的なエラーハンドリング
 */
class HttpErrorHandler implements HttpErrorHandlerContract
{
    /**
     * constructor
     *
     * @param ApplicationEnvironment $environment
     * @param Loggers $loggers
     */
    public function __construct(
        private readonly ApplicationEnvironment $environment,
        private readonly Loggers $loggers,
    ) {
        //
    }

    /**
     * エラーハンドリングをしてレスポンスを返す
     *
     * @param Request $request
     * @param Throwable $error
     * @return Response
     */
    public function handle(Request $request, Throwable $error): Response
    {
        $this->report($error);

        return match (true) {
            $error instanceof HttpException => $this->handleHttpException($error),
            default => $this->environment->debug
                ? throw $error
                : $this->handleHttpException(
                    new InternalServerErrorHttpException(
                        message: $error->getMessage(),
                        previous: $error,
                    ),
                ),
        };
    }

    /**
     * HttpExceptionをハンドリングする
     *
     * @param HttpException $error
     * @return Response
     */
    protected function handleHttpException(HttpException $error): Response
    {
        return new Response(
            content: $error->getMessage(),
            status: $error->getStatusCode(),
            headers: $error->getHeaders(),
        );
    }

    /**
     * Errorのレポートを出力する
     *
     * @return void
     */
    protected function report(Throwable $error): void
    {
        $this->loggers
            ->get('error')
            ->error($error);
    }
}
