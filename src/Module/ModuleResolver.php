<?php

namespace Takemo101\Egg\Module;

use Takemo101\Egg\Module\ModuleContract;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * モジュールの解決
 */
final class ModuleResolver
{
    /**
     * constructor
     *
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly ContainerContract $container,
    ) {
        //
    }

    /**
     * モジュールの解決
     * モジュールクラスに解決する
     * 解決できない場合はnullを返す
     *
     * @param class-string|object $module
     * @return ModuleContract|null
     */
    public function resolve(string|object $module): ?ModuleContract
    {
        return is_string($module)
            ? $this->resolveObject(
                $this->container->make($module),
            )
            : $this->resolveObject($module);
    }

    /**
     * オブジェクトの解決
     * 解決できない場合はnullを返す
     *
     * @param mixed $module
     * @return ModuleContract|null
     */
    private function resolveObject(mixed $module): ?ModuleContract
    {
        if (is_object($module) && $module instanceof ModuleContract) {
            return $module;
        }

        return null;
    }
}
