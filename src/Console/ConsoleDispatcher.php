<?php

namespace Takemo101\Egg\Console;

use RuntimeException;
use LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;

final class ConsoleDispatcher implements ConsoleDispatcherContract
{
    /**
     * constructor
     *
     * @param Application $application
     * @param CommandCollection $commands
     * @param CommandResolver $resolver
     */
    public function __construct(
        private readonly Application $application,
        private readonly CommandCollection $commands,
        private readonly CommandResolver $resolver,
    ) {
        //
    }

    /**
     * console dispatch
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     * @throws RuntimeException|LogicException
     */
    public function dispatch(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        foreach ($this->commands->commands as $command) {
            $resolved = $this->resolver->resolve($command);
            if (!$resolved) {
                throw new RuntimeException("{$command} is not command class");
            }

            $this->application->add($resolved);
        }

        return $this->application->run($input, $output);
    }
}
