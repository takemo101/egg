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
     * @param Commands $commands
     * @param CommandResolver $resolver
     */
    public function __construct(
        private readonly Application $application,
        private readonly Commands $commands,
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
        foreach ($this->commands->classes as $command) {
            $resolved = $this->resolver->resolve($command);
            if (!$resolved) {
                $name = $this->getClassName($command);
                throw new RuntimeException("{$name} is not command class");
            }

            $this->application->add($resolved);
        }

        return $this->application->run($input, $output);
    }

    /**
     * クラス名を取得する
     *
     * @param string|object $class
     * @return string
     */
    private function getClassName(string|object $class): string
    {
        return is_object($class) ? get_class($class) : $class;
    }
}
