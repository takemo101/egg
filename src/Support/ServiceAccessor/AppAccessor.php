<?php

namespace Takemo101\Egg\Support\ServiceAccessor;

/**
 * アプリケーションへのアクセス
 *
 * @method static \Takemo101\Egg\Kernel\ApplicationEnvironment env()
 * @method static \Takemo101\Egg\Kernel\ApplicationPath path()
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract container()
 * @see \Takemo101\Egg\Kernel\Application
 */
final class AppAccessor extends ServiceAccessor
{
    /**
     * ServiceLocatorに登録しているキーを取得する
     *
     * @return string
     */
    protected static function getServiceAccessKey(): string
    {
        return 'app';
    }
}
