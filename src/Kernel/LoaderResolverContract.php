<?php

namespace Takemo101\Egg\Kernel;

/**
 * Loaderのインスタンス生成を解決
 */
interface LoaderResolverContract
{
    /**
     * Loaderの生成処理
     * Loaderは文字列として渡される
     *
     * @param string $loader
     * @return LoaderContract
     */
    public function resolve(string $loader): LoaderContract;
}
