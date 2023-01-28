<?php

namespace Test;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\ApplicationPath;
use Takemo101\Egg\Kernel\Loader\HttpLoader;

/**
 * file test
 */
class AppTestCase extends PHPUnitTestCase
{
    /**
     * @var Application
     */
    protected readonly Application $app;

    protected function setUp(): void
    {
        $this->app = new Application(
            pathSetting: new ApplicationPath(
                basePath: dirname(__DIR__),
                dotenv: '.testing.env',
            ),
        );

        $this->app->addLoader(
            HttpLoader::class,
        );

        $this->app->boot();
    }
}
