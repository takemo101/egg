<?php

namespace Takemo101\Egg\Support\ServiceAccessor;

/**
 * アプリケーションへのアクセス
 *
 * @method static \Takemo101\Egg\Routing\RouteMatchResult|null match(string $uri, string $method)
 * @method static string route(string $name, array<string,mixed> $parameter = [])
 * @method static \Takemo101\Egg\Routing\RouterContract addMatchTypes(array<string,string> $matchTypes)
 * @see \Takemo101\Egg\Routing\RouterContract
 */
final class RouterAccessor extends ServiceAccessor
{
    /**
     * ServiceLocatorに登録しているキーを取得する
     *
     * @return string
     */
    protected static function getServiceAccessKey(): string
    {
        return 'router';
    }
}
