<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Httpのエラーハンドリングをする
 */
interface HttpErrorHandlerContract
{
    /**
     * エラーハンドリングをしてレスポンスを返す
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     */
    public function handle(Request $request, Throwable $error): Response;
}
