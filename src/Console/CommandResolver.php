<?php

namespace Takemo101\Egg\Console;

use Symfony\Component\Console\Command\Command;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * コマンドの解決
 */
final class CommandResolver
{
    /**
     * constructor
     *
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly ContainerContract $container,
    ) {
        //
    }

    /**
     * コマンドの解決
     * コマンドクラスに解決する
     * 解決できない場合はnullを返す
     *
     * @param class-string|object ...$commands
     * @return Command|null
     */
    public function resolve(string|object $command): ?Command
    {
        return is_string($command)
            ? $this->resolveObject(
                $this->container->make($command),
            )
            : $this->resolveObject($command);
    }

    /**
     * オブジェクトの解決
     * 解決できない場合はnullを返す
     *
     * @param mixed $command
     * @return Command|null
     */
    private function resolveObject(mixed $command): ?Command
    {
        if (is_object($command) && $command instanceof Command) {
            return $command;
        }

        return null;
    }
}
