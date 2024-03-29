<?php

namespace Test\Kernel;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\ApplicationPath;

/**
 * application test
 */
class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function Applicationを実行する__OK()
    {
        $app = new Application(
            path: new ApplicationPath(
                base: dirname(__DIR__, 2),
                dotenv: '.testing.env',
            ),
        );

        $app->boot();

        $this->assertTrue(
            $app->isBooted(),
            'Applicationが起動している',
        );
    }
}
