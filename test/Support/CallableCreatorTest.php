<?php

namespace Test\Support;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Routing\Shared\Handler;
use Takemo101\Egg\Support\Injector\Container;
use Takemo101\Egg\Support\Shared\CallableCreator;

/**
 * callable creator test
 */
class CallableCreatorTest extends TestCase
{
    /**
     * @test
     */
    public function クラス名とメソッド名からなる配列からCallableを生成__OK()
    {
        $container = new Container();

        $creator = new CallableCreator(
            $container,
        );

        $callable = $creator->create(new Handler([TestController::class, 'index']));

        $data = $container->call($callable);

        $this->assertEquals(
            TestController::$data,
            $data,
            '配列からCallableを生成＆実行して値を取得できる',
        );
    }

    /**
     * @test
     */
    public function クラス名のみの配列からCallableを生成__OK()
    {
        $container = new Container();

        $creator = new CallableCreator(
            $container,
        );

        $callable = $creator->create(new Handler([TestController::class]));

        $data = $container->call($callable);

        $this->assertEquals(
            TestController::$data,
            $data,
            '配列からCallableを生成＆実行して値を取得できる',
        );
    }
}

class TestController
{
    public static string $data = 'test';

    public function index()
    {
        return self::$data;
    }

    public function __invoke()
    {
        return self::$data;
    }
}
