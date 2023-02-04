<?php

namespace Takemo101\Egg\Console\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Egg\Kernel\Application;

/**
 * ただバージョンを表示するだけのコマンド
 */
final class VersionCommand extends EggCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('version')
            ->setDescription('Display version');
    }

    /**
     * コマンド実行
     *
     * @param OutputInterface $output
     * @return integer
     */
    public function handle(OutputInterface $output)
    {
        $output->writeln('<info>------------------</info>');
        $output->writeln(Application::Name . ' <comment>' . Application::Version . '</comment>');
        $output->writeln('Enjoy!');
        $output->writeln('<info>------------------</info>');

        return self::SUCCESS;
    }
}
