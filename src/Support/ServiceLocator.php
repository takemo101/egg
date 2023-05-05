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
     * インスタンス初期化
     *
     * @return self
     */
    public static function init(): self
    {
        return self::$instance = new self();
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

        return self::init();
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
     * 既に同じキーが存在する場合は例外を投げる
     *
     * @param string $key
     * @param object $object
     * @return void
     * @throws RuntimeException
     */
    public static function set(string $key, object $object): void
    {
        $container = self::instance()->container;

        if ($container->has($key)) {
            throw new RuntimeException("{$key} is already set");
        }

        $container->set($key, $object);
    }


    /**
     * キーに対するサービスが存在するか
     *
     * @param string $key
     * @return boolean
     */
    public static function has(string $key): bool
    {
        return self::instance()
            ->container
            ->has($key);
    }


    /**
     * サービスを削除する
     *
     * @param string $key
     * @return void
     */
    public static function clear(string $key): void
    {
        self::instance()
            ->container
            ->forget($key);
    }
}
