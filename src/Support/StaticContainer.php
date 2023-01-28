<?php

namespace Takemo101\Egg\Support;

use RuntimeException;
use Takemo101\Egg\Support\Arr\ArrAccess;

/**
 * 静的コンテナ
 *
 * Injectorを利用できない場合に
 * オブジェクトを取得するのに利用する
 */
final class StaticContainer
{
    /**
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * constructor
     *
     * @param ArrAccess<mixed> $container
     */
    private function __construct(
        public readonly ArrAccess $container = new ArrAccess(),
    ) {
        //
    }

    /**
     * シングルトンのインスタンスを取得する
     *
     * @return self
     */
    public static function instance(): self
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new self();
    }

    /**
     * 値を取得する
     *
     * @param string $key
     * @return object
     * @throws RuntimeException
     */
    public static function get(string $key): object
    {
        $object = self::instance()->container->get($key);

        if (is_object($object)) {
            return $object;
        }

        throw new RuntimeException("{$key} is not object");
    }

    /**
     * 値をセットする
     *
     * @param string $key
     * @param object $object
     * @return void
     */
    public static function set(string $key, object $object): void
    {
        self::instance()->container->set($key, $object);
    }
}
