<?php

namespace Test\Kernel\Module;

use Test\AppTestCase;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\ApplicationPath;
use Takemo101\Egg\Module\Module;
use Takemo101\Egg\Module\ModuleBooter;
use Takemo101\Egg\Module\ModuleContract;
use Takemo101\Egg\Module\ModuleResolver;
use Takemo101\Egg\Module\Modules;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;

/**
 * module test
 */
class ModuleTest extends AppTestCase
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

    /**
     * @test
     */
    public function コンフィグをマージする__OK()
    {
        $module = $this->app->container->make(TestMergeConfigModule::class);

        $config = $this->app->container->make(ConfigRepositoryContract::class);

        $testKey = 'test-key';
        $testData = 'test';
        $baseConfig = [
            $testKey => $testData,
        ];

        $config->set(TestMergeConfigModule::Key, $baseConfig);

        $module->boot();

        $this->assertEquals(
            $testData,
            $config->get(TestMergeConfigModule::Key . '.' . $testKey),
            'コンフィグが設定されている',
        );

        $this->assertNotContainsEquals(
            $baseConfig,
            $config->get(TestMergeConfigModule::Key),
            'コンフィグがマージされている',
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

class TestMergeConfigModule extends Module
{
    public const Key = 'test';

    public function boot(): void
    {
        $this->mergeConfig(self::Key, dirname(__DIR__, 1) . '/resource/config/test.php');
    }
}
