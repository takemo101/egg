<?php

namespace Test\Kernel;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Kernel\Bootstrap;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Kernel\LoaderResolver;
use Takemo101\Egg\Support\Injector\Container;

/**
 * bootstrap test
 */
class BootstrapTest extends TestCase
{
    public static int $counter = 0;

    /**
     * @test
     */
    public function Loaderを実行する__OK()
    {
        $container = new Container();

        $bootstrap = new Bootstrap(
            new LoaderResolver($container),
        );

        $bootstrap->addLoader(
            MockLoaderA::class,
            MockLoaderB::class,
        );

        $bootstrap->boot();

        $this->assertEquals(
            self::$counter,
            2,
            'Loaderが2回実行されている',
        );
    }
}

class MockLoaderA implements LoaderContract
{
    /**
     * ロード処理をする
     *
     * @return void
     */
    public function load(): void
    {
        BootstrapTest::$counter++;
    }
}

class MockLoaderB implements LoaderContract
{
    /**
     * ロード処理をする
     *
     * @return void
     */
    public function load(): void
    {
        BootstrapTest::$counter++;
    }
}
