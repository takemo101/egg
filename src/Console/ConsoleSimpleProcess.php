<?php

namespace Takemo101\Egg\Console;

use Takemo101\Egg\Console\ConsoleDispatcherContract;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Consoleで引数の受け取りから
 * プロセスを終了するまでの処理を簡単に実行する
 */
final class ConsoleSimpleProcess
{
    /**
     * constructor
     *
     * @param ConsoleDispatcherContract $dispatcher
     */
    public function __construct(
        private readonly ConsoleDispatcherContract $dispatcher,
    ) {
        //
    }

    /**
     * プロセスを実行する
     *
     * @return void
     */
    public function process(): void
    {
        $status = $this->dispatcher->dispatch(
            input: new ArgvInput(),
            output: new ConsoleOutput(),
        );

        exit($status);
    }
}
