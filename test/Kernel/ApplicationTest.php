<?php

namespace Test\Kernel;

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\ApplicationPath;
use Test\AppTestCase;

/**
 * application test
 */
class ApplicationTest extends AppTestCase
{
    /**
     * @test
     */
    public function Applicationを実行する__OK()
    {
        $app = new Application(
            pathSetting: new ApplicationPath(
                basePath: dirname(__DIR__, 2),
            ),
        );

        $app->boot();

        $this->assertTrue(
            $app->isBooted(),
            'Applicationが起動している',
        );
    }
}
