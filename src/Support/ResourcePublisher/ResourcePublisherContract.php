<?php

namespace Takemo101\Egg\Support\ResourcePublisher;

/**
 * リソースの公開をする
 */
interface ResourcePublisherContract
{
    /**
     * リソースを公開する
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public function publish(
        string $from,
        string $to,
    ): void;
}
