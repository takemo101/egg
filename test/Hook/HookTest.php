<?php

namespace Test\Hook;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\Injector\Container;
use Takemo101\Egg\Support\Shared\CallableCreator;

/**
 * hook test
 */
class HookTest extends TestCase
{
    private Hook $hook;

    private string $result = 'result';

    protected function setUp(): void
    {
        $this->hook =  new Hook(new CallableCreator(new Container()));
    }

    /**
     * @test
     */
    public function フィルタの実行__OK()
    {
        $testA = 'a';
        $testB = 'b';

        $this->hook->register('test-a', fn (string $value) => $value);

        $this->hook->register('test-b', fn (string $value) => $value);

        $testAResult = $this->hook->applyFilter('test-a', $testA);

        $this->assertEquals($testA, $testAResult, 'フィルタAの実行値と結果が一致する');

        $testBResult = $this->hook->applyFilter('test-b', $testB);

        $this->assertEquals($testB, $testBResult, 'フィルタBの実行値と結果が一致する');
    }

    /**
     * @test
     */
    public function アクションの実行__OK()
    {
        $testA = 'a';
        $testB = 'b';

        $this->hook->register('test-a', fn (string $value) => $this->result = $value);

        $this->hook->register('test-b', fn (string $value) => $this->result = $value);

        $this->hook->doAction('test-a', $testA);

        $this->assertEquals($testA, $this->result, 'アクションAの実行値と結果が一致する');

        $this->hook->doAction('test-b', $testB);

        $this->assertEquals($testB, $this->result, 'アクションBの実行値と結果が一致する');
    }
}
