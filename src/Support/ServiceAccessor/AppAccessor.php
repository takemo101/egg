<?php

namespace Takemo101\Egg\Support\ServiceAccessor;

/**
 * アプリケーションへのアクセス
 *
 * @method static \Takemo101\Egg\Kernel\ApplicationEnvironment env()
 * @method static \Takemo101\Egg\Kernel\ApplicationPath path()
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract container()
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract alias(string $class, string $alias)
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract instance(string $label, mixed $instance)
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract singleton(string $label, Closure|string|null $callback = null)
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract bind(string $label, Closure|string|null $callback = null)
 * @method static boolean has(string $label)
 * @method static void clear()
 * @method static mixed make(string $label, mixed[] $options = [])
 * @method static mixed call(callable $callable, mixed[] $options = [])
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
