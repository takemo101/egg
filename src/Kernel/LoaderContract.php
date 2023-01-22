<?php

namespace Takemo101\Egg\Kernel;

/**
 * bootstrapで実行するLoader
 */
interface LoaderContract
{
    /**
     * ロード処理をする
     *
     * @return void
     */
    public function load(): void;
}
