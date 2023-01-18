<?php

namespace Takemo101\Egg\Kernel\Loader;

use Symfony\Component\ErrorHandler\Debug;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Support\Environment;

/**
 * Http関連
 */
final class ErrorLoader implements LoaderContract
{
    /**
     * constructor
     *
     * @param Environment $env
     */
    public function __construct(
        private readonly Environment $env,
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
        if ($this->env->get('APP_DEBUG')) {
            Debug::enable();
        }
    }
}
