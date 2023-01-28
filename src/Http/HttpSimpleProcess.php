<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Httpでリクエストの受け取りから
 * レスポンスを返すまでの処理を簡単に実行する
 */
final class HttpSimpleProcess
{
    /**
     * constructor
     *
     * @param HttpDispatcherContract $dispatcher
     * @param ResponseSenderContract $sender
     */
    public function __construct(
        private readonly HttpDispatcherContract $dispatcher,
        private readonly ResponseSenderContract $sender,
    ) {
        //
    }

    /**
     * プロセスを実行する
     *
     * @return void
     */
    public function process(): void
    {
        // ルーティングを処理する
        $response = $this->dispatcher->dispatch(
            request: Request::createFromGlobals(),
            response: new Response(),
        );

        $this->sender->send($response);
    }
}
