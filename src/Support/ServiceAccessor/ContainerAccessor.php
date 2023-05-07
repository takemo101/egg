<?php

namespace Takemo101\Egg\Support\ServiceAccessor;

/**
 * サービスコンテナへのアクセス
 *
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract alias(string $class, string $alias)
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract instance(string $label, mixed $instance)
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract singleton(string $label, Closure|string|null $callback = null)
 * @method static \Takemo101\Egg\Support\Injector\ContainerContract bind(string $label, Closure|string|null $callback = null)
 * @method static boolean has(string $label)
 * @method static void clear()
 * @method static mixed make(string $label, array $parameters = [])
 * @method mixed call(callable $callback, array $options = [])
 * @see \Takemo101\Egg\Support\Injector\ContainerContract
 */
final class ContainerAccessor extends ServiceAccessor
{
    /**
     * ServiceLocatorに登録しているキーを取得する
     *
     * @return string
     */
    protected static function getServiceAccessKey(): string
    {
        return 'container';
    }
}
