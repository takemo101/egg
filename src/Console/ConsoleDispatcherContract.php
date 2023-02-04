<?php

namespace Takemo101\Egg\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * WebアプリケーションのConsoleディスパッチャ
 * コマンドを実行する
 */
interface ConsoleDispatcherContract
{
    /**
     * console dispatch
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer コマンドの実行結果の終了コード
     */
    public function dispatch(
        InputInterface $input,
        OutputInterface $output,
    ): int;
}
