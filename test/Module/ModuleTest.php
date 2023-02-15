<?php

namespace Test\Kernel;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\ApplicationPath;
use Takemo101\Egg\Module\ModuleBooter;
use Takemo101\Egg\Module\ModuleContract;
use Takemo101\Egg\Module\ModuleResolver;
use Takemo101\Egg\Module\Modules;

/**
 * module test
 */
class ModuleTest extends TestCase
{
    /**
     * @test
     */
    public function モジュールを起動する__OK()
    {
        $strings = [
            'first',
            'second',
        ];

        $beforeString = TestModule::$test;

        $modules = new Modules(
            new TestModule($strings[0]),
            new TestModule($strings[1]),
        );

        $app = new Application(
            pathSetting: new ApplicationPath(
                basePath: dirname(__DIR__, 2),
                dotenv: '.testing.env',
            ),
        );

        (new ModuleBooter(
            modules: $modules,
            resolver: new ModuleResolver($app->container),
        ))->boot();

        $this->assertSame(
            $beforeString . implode('', $strings),
            TestModule::$test,
            'モジュールが起動している',
        );
    }
}

class TestModule implements ModuleContract
{
    public static string $test = 'boot';

    public function __construct(
        private readonly string $addString,
    ) {
        //
    }

    public function boot(): void
    {
        self::$test .= $this->addString;
    }
}
