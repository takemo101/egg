<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * レスポンスを送信する
 */
final class ResponseResponder implements ResponseResponderContract
{
    /**
     * レスポンスを送信する
     *
     * @param Response $response
     * @return void
     */
    public function respond(Response $response): void
    {
        $response->send();
    }
}
