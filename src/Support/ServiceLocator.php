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
     * @param ArrAccess<mixed> $services
     * @param ArrAccess<callable> $factories
     */
    private function __construct(
        private readonly ArrAccess $services = new ArrAccess(),
        private readonly ArrAccess $factories = new ArrAccess(),
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
        $services = self::instance()->services;
        $factories = self::instance()->factories;

        if ($services->has($key)) {
            $object = $services->get($key);
        } else if ($factories->has($key)) {
            $factory = $factories->get($key);

            $object = call_user_func($factory);
            $services->set($key, $object);
        } else {
            throw new RuntimeException("{$key} is not found");
        }

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
        $services = self::instance()->services;

        if ($services->has($key)) {
            throw new RuntimeException("{$key} is already set");
        }

        $services->set($key, $object);
    }

    /**
     * サービスの生成処理をセットする
     * 既に同じキーが存在する場合は例外を投げる
     *
     * @param string $key
     * @param callable $factory
     * @return void
     */
    public static function factory(string $key, callable $factory): void
    {
        $factories = self::instance()->factories;

        if ($factories->has($key)) {
            throw new RuntimeException("{$key} is already set");
        }

        $factories->set($key, $factory);
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
            ->services
            ->has($key)
            || self::instance()
            ->factories
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
            ->services
            ->forget($key);

        self::instance()
            ->factories
            ->forget($key);
    }
}
