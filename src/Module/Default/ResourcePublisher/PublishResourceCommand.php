<?php

namespace Takemo101\Egg\Module\Default\ResourcePublisher;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Egg\Console\Command\EggCommand;

/**
 * ただバージョンを表示するだけのコマンド
 */
final class PublishResourceCommand extends EggCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('resource:publish')
            ->setDescription('Publish resource')
            ->addArgument('tag', InputArgument::REQUIRED, 'resource tag name');
    }

    /**
     * コマンド実行
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    public function handle(
        InputInterface $input,
        OutputInterface $output,
        PublishResources $resources,
        ResourcePublisherContract $publisher,
    ) {
        $tag = (string)$input->getArgument('tag');

        if (!$resources->has($tag)) {
            $output->writeln('<error>Resource tag not found</error>');
            return self::FAILURE;
        }

        foreach ($resources->get($tag) as $from => $to) {
            $output->writeln("<info>publish: {$from} -> {$to}</info>");
            $publisher->publish($from, $to);
        }

        return self::SUCCESS;
    }
}
