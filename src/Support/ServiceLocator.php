<?php

namespace Takemo101\Egg\Support;

use RuntimeException;
use Takemo101\Egg\Support\Arr\ArrAccess;

/**
 * サービスロケーター
 *
 * Injectorを利用できない場合に
 * オブジェクトを取得するのに利用する
 */
final class ServiceLocator
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
        private readonly ArrAccess $container = new ArrAccess(),
    ) {
        //
    }

    /**
     * シングルトンのインスタンスを取得する
     *
     * @return self
     */
    private static function instance(): self
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new self();
    }

    /**
     * サービスを取得する
     *
     * @param string $key
     * @return object
     * @throws RuntimeException
     */
    public static function get(string $key): object
    {
        $object = self::instance()
            ->container
            ->get($key);

        if (is_object($object)) {
            return $object;
        }

        throw new RuntimeException("{$key} is not object");
    }

    /**
     * サービスをセットする
     *
     * @param string $key
     * @param object $object
     * @return self
     */
    public static function set(string $key, object $object): self
    {
        self::instance()
            ->container
            ->set($key, $object);

        $class = get_class($object);

        // クラス名とキーが異なる場合はクラス名でも登録する
        if ($key !== $class) {
            self::instance()
                ->container
                ->set($class, $object);
        }

        return self::instance();
    }

    /**
     * サービスを削除する
     *
     * @param string $key
     * @return self
     */
    public static function clear(string $key): self
    {
        self::instance()
            ->container
            ->forget($key);

        return self::instance();
    }
}
