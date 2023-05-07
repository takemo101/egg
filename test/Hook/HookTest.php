<?php

namespace Test\Hook;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\Hook\HookDefinitionDataFilter;
use Takemo101\Egg\Support\Injector\Container;
use Takemo101\Egg\Support\Injector\DefinitionDataFilters;
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

        $this->hook->add('test-a', fn (string $value) => $value);

        $this->hook->add('test-b', fn (string $value) => $value);

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

        $this->hook->add('test-a', fn (string $value) => $this->result = $value);

        $this->hook->add('test-b', fn (string $value) => $this->result = $value);

        $this->hook->doAction('test-a', $testA);

        $this->assertEquals($testA, $this->result, 'アクションAの実行値と結果が一致する');

        $this->hook->doAction('test-b', $testB);

        $this->assertEquals($testB, $this->result, 'アクションBの実行値と結果が一致する');
    }

    /**
     * @test
     */
    public function DIでのフィルタの実行__OK()
    {
        [$container, $hook] = $this->createMockInstance();

        $container->bind(HookTargetClass::class, fn () => new HookTargetClass('a'));

        $data = 'b';

        $hook->add(HookTargetClass::class, fn (HookTargetClass $target) => $target->setA($data));

        $target = $container->make(HookTargetClass::class);

        $this->assertEquals(
            $data,
            $target->getA(),
            'フィルタの実行値と結果が一致する',
        );
    }

    /**
     * @test
     */
    public function DIでのフィルタを別名で実行__OK()
    {
        [$container, $hook] = $this->createMockInstance();

        $container->bind(HookTargetClass::class, fn () => new HookTargetClass('a'));
        $container->alias(HookTargetClass::class, 'target');

        $data = 'b';

        $hook->add('target', fn (HookTargetClass $target) => $target->setA($data));


        $target = $container->make(HookTargetClass::class);

        $this->assertEquals(
            $data,
            $target->getA(),
            'フィルタの実行値と結果が一致する',
        );
    }

    /**
     * @test
     */
    public function DIでのフィルタをinstanceで実行__OK()
    {
        [$container, $hook] = $this->createMockInstance();

        $data = 'b';
        $hook->add('target', fn (HookTargetClass $target) => $target->setA($data));

        $container->alias(HookTargetClass::class, 'target');
        $container->instance(HookTargetClass::class, new HookTargetClass('a'));

        $target = $container->make(HookTargetClass::class);

        $this->assertEquals(
            $data,
            $target->getA(),
            'フィルタの実行値と結果が一致する',
        );
    }

    /**
     * テスト用のモックを作成
     *
     * @return array{0:Container,1:Hook}
     */
    private function createMockInstance(): array
    {
        $filters = new DefinitionDataFilters();
        $container = new Container($filters);
        $hook = new Hook(new CallableCreator($container));

        $filters->add(new HookDefinitionDataFilter($hook));

        return [
            $container,
            $hook,
        ];
    }
}

class HookTargetClass
{
    public function __construct(
        private string $a,
    ) {
        //
    }

    public function getA()
    {
        return $this->a;
    }

    public function setA(string $a)
    {
        $this->a = $a;

        return $this;
    }
}
