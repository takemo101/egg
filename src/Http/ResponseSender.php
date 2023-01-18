<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * レスポンスを送信する
 */
final class ResponseSender implements ResponseSenderContract
{
    /**
     * レスポンスを送信する
     *
     * @param Response $response
     * @return void
     */
    public function send(Response $response): void
    {
        $response->send();
    }
}
