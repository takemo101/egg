<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * レスポンス送信
 */
interface ResponseSenderContract
{
    public function send(Response $response): void;
}
