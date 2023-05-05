<?php

namespace Test\Support;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\ServiceLocator;

/**
 * service locator test
 */
class ServiceLocatorTest extends TestCase
{
    protected function setUp(): void
    {
        ServiceLocator::init();
    }

    /**
     * @test
     */
    public function サービスを登録して取り出す__OK()
    {
        $service = new TestService();

        $key = 'test';

        ServiceLocator::set($key, $service);

        $this->assertSame(
            $service,
            ServiceLocator::get($key),
            'サービスを取得できる',
        );
    }

    /**
     * @test
     */
    public function サービスを登録して削除する__OK()
    {
        $service = new TestService();

        $key = 'test';

        ServiceLocator::set($key, $service);

        $this->assertTrue(
            ServiceLocator::has($key),
            'サービスが存在している',
        );

        ServiceLocator::clear($key);

        $this->assertFalse(
            ServiceLocator::has($key),
            'サービスを削除したので存在していない',
        );
    }
}

final class TestService
{
    //
}
