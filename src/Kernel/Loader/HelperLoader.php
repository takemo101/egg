<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;

/**
 * ヘルパー関数関連
 */
final class HelperLoader implements LoaderContract
{
    /**
     * constructor
     *
     * @param Application $app
     */
    public function __construct(
        private readonly Application $app,
    ) {
        //
    }

    /**
     * ロード処理をする
     *
     * @return void
     */
    public function load(): void
    {
        require __DIR__ . '/../../helper.php';
    }
}
