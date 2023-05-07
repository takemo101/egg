<?php

namespace Test\Console;

use Symfony\Component\Console\Command\Command;
use Takemo101\Egg\Console\ConsoleDispatcher;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Test\AppTestCase;

/**
 * console test
 */
class ConsoleDispatcherTest extends AppTestCase
{
    /**
     * @test
     */
    public function ConsoleDispatcherを実行する__OK()
    {
        /** @var ConsoleDispatcher */
        $dispatcher = $this->app->make(ConsoleDispatcher::class);

        $exitCode = $dispatcher->dispatch(
            input: new StringInput('version'),
            output: new NullOutput(),
        );

        $this->assertEquals($exitCode, Command::SUCCESS, '終了コードがSUCCESSで正常');
    }
}
