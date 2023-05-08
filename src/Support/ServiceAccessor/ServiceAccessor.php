<?php

namespace Takemo101\Egg\Support\ServiceAccessor;

use Takemo101\Egg\Support\ServiceLocator;
use RuntimeException;

/**
 * staticメソッドでサービスにアクセスする
 */
abstract class ServiceAccessor
{
    /**
     * ServiceLocatorに登録しているキーを取得する
     *
     * @return string
     */
    abstract protected static function getServiceAccessKey(): string;

    /**
     * アクセスするサービスを取得する
     *
     * @return mixed
     */
    public static function toAccessService(): mixed
    {
        return ServiceLocator::get(static::getServiceAccessKey());
    }

    /**
     * @param string $method
     * @param mixed[] $args
     * @return mixed
     * @throws RuntimeException
     */
    public static function __callStatic(string $method, array $args)
    {
        $instance = static::toAccessService();

        return $instance->$method(...$args);
    }
}
