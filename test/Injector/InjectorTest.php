<?php

namespace Test\Injector;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\Injector\Container;

/**
 * container test
 */
class InjectorTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container =  new Container();
    }

    /**
     * @test
     */
    public function 文字列での関連付け__ok()
    {
        $this->container->bind(MockTargetA::class);
        /**
         * @var MockTargetA $a
         */
        $a = $this->container->make(MockTargetA::class);

        $this->assertEquals(get_class($a), MockTargetA::class);

        $data = 'c';
        $a->setA($data);

        $a = $this->container->make(MockTargetA::class);
        /**
         * @var MockTargetA $a
         */
        $this->assertNotEquals($a->getA(), $data);

        $this->container->singleton(MockTargetB::class);
        /**
         * @var MockTargetB $b
         */
        $b = $this->container->make(MockTargetB::class);

        $this->assertEquals(get_class($b), MockTargetB::class);

        $data = 'g';
        $b->setB($data);

        /**
         * @var MockTargetB $b
         */
        $b = $this->container->make(MockTargetB::class);

        $this->assertEquals($b->getB(), $data);

        $alias = 'b';
        $this->container->alias(MockTargetB::class, $alias);

        /**
         * @var MockTargetB $b
         */
        $b = $this->container->make($alias);

        $this->assertEquals(get_class($b), MockTargetB::class);
        $this->assertEquals($b->getB(), $data);

        $this->assertTrue($this->container->has(MockTargetB::class));
        $this->assertTrue($this->container->has($alias));

        /**
         * @var MockClass $c
         */
        $c = $this->container->make(MockClass::class);
        $this->assertEquals(get_class($c), MockClass::class);
        $this->assertTrue($this->container->has(MockClass::class));


        $data = 'hello';

        /**
         * @var MockClass $c
         */
        $c = $this->container->make(MockClass::class, ['c' => $data]);
        $this->assertEquals($c->getC(), $data);

        $this->container->clear();

        $this->assertTrue(!$this->container->has(MockTargetB::class));
        $this->assertTrue(!$this->container->has($alias));
    }

    /**
     * @test
     */
    public function クロージャでの関連付け__ok()
    {
        $this->container->bind(MockTargetA::class, function ($c) {
            return new MockTargetA();
        });
        /**
         * @var MockTargetA $a
         */
        $a = $this->container->make(MockTargetA::class);

        $this->assertEquals(get_class($a), MockTargetA::class);

        $data = 'c';
        $a->setA($data);

        /**
         * @var MockTargetA $a
         */
        $a = $this->container->make(MockTargetA::class);

        $this->assertNotEquals($a->getA(), $data);

        $this->container->singleton(MockTargetB::class, function ($c) {
            return new MockTargetB();
        });

        /**
         * @var MockTargetB $b
         */
        $b = $this->container->make(MockTargetB::class);

        $this->assertEquals(get_class($b), MockTargetB::class);

        $data = 'g';
        $b->setB($data);

        /**
         * @var MockTargetB $b
         */
        $b = $this->container->make(MockTargetB::class);

        $this->assertEquals($b->getB(), $data);

        $alias = 'b';
        $this->container->alias(MockTargetB::class, $alias);

        /**
         * @var MockTargetB $b
         */
        $b = $this->container->make($alias);

        $this->assertEquals(get_class($b), MockTargetB::class);
        $this->assertEquals($b->getB(), $data);

        $this->assertTrue($this->container->has(MockTargetB::class));
        $this->assertTrue($this->container->has($alias));

        $this->container->clear();

        $this->assertTrue(!$this->container->has(MockTargetB::class));
        $this->assertTrue(!$this->container->has($alias));
    }

    /**
     * @test
     */
    public function 呼び出し__ok()
    {
        $this->container->bind(MockTargetA::class);
        $this->container->singleton(MockTargetB::class);

        /**
         * @var MockTargetB $b
         */
        $b = $this->container->make(MockTargetB::class);
        $data = 'inject b';
        $b->setB($data);

        /**
         * @var MockTargetA $a
         */
        $a = $this->container->make(MockTargetA::class);

        $this->container->call([$a, 'setB']);

        $this->assertEquals($a->getA(), $data);
        $this->assertEquals($this->container->call($a), $data);
        $this->assertEquals($this->container->call(function (MockTargetB $b) {
            return $b->getB();
        }), $data);

        $this->assertEquals($this->container->call(function ($b) {
            return $b;
        }, [
            'b' => $data,
        ]), $data);
    }
}

class MockTargetA
{
    private $a = 'a';

    public function setA($a)
    {
        $this->a = $a;
    }

    public function getA()
    {
        return $this->a;
    }

    public function setB(MockTargetB $b)
    {
        $this->a = $b->getB();
    }

    public function __invoke()
    {
        return $this->a;
    }
}

class MockTargetB
{
    private $b = 'b';

    public function setB($b)
    {
        $this->b = $b;
    }

    public function getB()
    {
        return $this->b;
    }
}

class MockClass
{
    public function __construct(
        private MockTargetB $b,
        private MockTargetA $a,
        private $c = 'C',
    ) {
        //
    }

    public function getB($b)
    {
        return $this->b;
    }

    public function getA()
    {
        return $this->a;
    }

    public function getC()
    {
        return $this->c;
    }
}
