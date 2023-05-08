<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * レスポンス送信
 */
interface ResponseResponderContract
{
    public function respond(Response $response): void;
}
