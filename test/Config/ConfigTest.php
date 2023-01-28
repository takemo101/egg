<?php

namespace Test\Arr;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\Config\ConfigRepository;
use Takemo101\Egg\Support\Filesystem\LocalSystem;

/**
 * config test
 */
class ConfigTest extends TestCase
{
    private ConfigRepository $repository;

    protected function setUp(): void
    {
        $filesystem =  new LocalSystem();

        $this->repository = new ConfigRepository(
            $filesystem,
            $filesystem->helper->join(__DIR__, 'resource'),
        );
    }

    /**
     * @test
     */
    public function コンフィグの値を取得__OK()
    {
        $get = $this->repository->get('config-a.a.b1');

        $this->assertEquals($get, 'b1');
    }

    /**
     * @test
     */
    public function コンフィグの値をセット__OK()
    {
        $data = 'test';

        $before = $this->repository->get('config-b.a.b1');

        $this->repository->set('config-b.a.b1', $data);

        $this->assertNotEquals(
            $before,
            $this->repository->get('config-b.a.b1'),
            'セット前の値とセット後の値が異なる'
        );

        $this->assertEquals(
            $data,
            $this->repository->get('config-b.a.b1'),
            'データがセットされている'
        );
    }
}
