<?php

namespace Takemo101\Egg\Console;

use Takemo101\Egg\Console\ConsoleDispatcherContract;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\Loader\ConsoleLoader;

/**
 * Consoleで引数の受け取りから
 * プロセスを終了するまでの処理を簡単に実行する
 */
final class ConsoleProcess
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
    public function run(): void
    {
        $status = $this->dispatcher->dispatch(
            input: new ArgvInput(),
            output: new ConsoleOutput(),
        );

        exit($status);
    }

    /**
     * アプリケーションから必要なLoaderを追加して
     * 起動してプロセスを生成する
     *
     * @param Application $app
     * @param class-string ...$loaders LoaderContractを実装したクラス文字列
     * @return self
     */
    public static function fromApplication(
        Application $app,
        string ...$loaders,
    ): self {
        $app->addLoader(
            ConsoleLoader::class,
            ...$loaders,
        )->boot();

        /** @var self */
        $process = $app->make(
            self::class,
        );

        return $process;
    }
}
