<?php

namespace Takemo101\Egg\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LogicException;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * コマンド基底クラス
 */
abstract class EggCommand extends Command
{
    /**
     * @var InputInterface|null
     */
    private ?InputInterface $input = null;

    /**
     * @var OutputInterface|null
     */
    private ?OutputInterface $output = null;

    /**
     * @param string|null $name
     *
     * @throws LogicException When the command name is empty
     */
    public function __construct(
        protected readonly ContainerContract $container,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    /**
     * execute command process
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (method_exists($this, 'handle')) {
            $this->input = $input;
            $this->output = $output;

            $this->container->instance(InputInterface::class, $input);
            $this->container->instance(OutputInterface::class, $output);

            /** @var integer */
            $exitCode = $this->container->call([$this, 'handle'], [
                'input' => $input,
                'output' => $output,
            ]);

            return $exitCode;
        }

        return self::SUCCESS;
    }

    /**
     * コマンド実行時の入力インターフェースを取得
     *
     * @return InputInterface
     */
    protected function input(): InputInterface
    {
        if ($this->input === null) {
            throw new LogicException('input is not set!');
        }

        return $this->input;
    }

    /**
     * コマンド実行時の出力インターフェースを取得
     *
     * @return OutputInterface
     */
    protected function output(): OutputInterface
    {
        if ($this->output === null) {
            throw new LogicException('output is not set!');
        }

        return $this->output;
    }
}
