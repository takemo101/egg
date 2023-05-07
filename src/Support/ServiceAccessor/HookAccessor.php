<?php

namespace Takemo101\Egg\Support\ServiceAccessor;

use Closure;

/**
 * フックへのアクセス
 *
 * @method static \Takemo101\Egg\Support\Hook\Hook on(string $tag, object|array|string $function, int $priority = \Takemo101\Egg\Support\Hook\HookFilter::DefaultPriority)
 * @method static \Takemo101\Egg\Support\Hook\Hook onBy(Closure $function, int $priority = \Takemo101\Egg\Support\Hook\HookFilter::DefaultPriority)
 * @method static mixed applyFilter(string $tag, mixed $parameter)
 * @method static void doAction(string $tag, mixed $parameter)
 * @see \Takemo101\Egg\Support\Hook\Hook
 */
final class HookAccessor extends ServiceAccessor
{
    /**
     * ServiceLocatorに登録しているキーを取得する
     *
     * @return string
     */
    protected static function getServiceAccessKey(): string
    {
        return 'hook';
    }
}
