<?php

namespace Takemo101\Egg\Module\Default\ResourcePublisher;

use Takemo101\Egg\Support\Filesystem\LocalSystemContract;

/**
 * ファイルシステムでリソースの公開をする
 */
final class ResourcePublisher implements ResourcePublisherContract
{
    /**
     * constructor
     *
     * @param LocalSystemContract $fs
     */
    public function __construct(
        private readonly LocalSystemContract $fs,
    ) {
        //
    }

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
    ): void {
        $this->fs->copy($from, $to);
    }
}
