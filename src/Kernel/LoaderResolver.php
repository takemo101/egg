<?php

namespace Takemo101\Egg\Kernel;

use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * Injectorを使用してLoaderを生成する
 */
final class LoaderResolver implements LoaderResolverContract
{
    /**
     * Undocumented function
     *
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly ContainerContract $container,
    ) {
        //
    }

    /**
     * Loaderの生成処理
     * Loaderは文字列として渡される
     *
     * @param string $loader
     * @return LoaderContract
     */
    public function resolve(string $loader): LoaderContract
    {
        /** @var LoaderContract */
        $instance = $this->container->make($loader);

        return $instance;
    }
}
