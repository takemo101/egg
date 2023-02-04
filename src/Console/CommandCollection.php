<?php

namespace Takemo101\Egg\Console;

/**
 * コマンドのコレクション
 */
final class CommandCollection
{
    /** @var array<class-string|object> */
    public readonly array $commands;

    /**
     * constructor
     *
     * @param class-string|object ...$commands
     */
    public function __construct(
        string|object ...$commands,
    ) {
        $this->commands = $commands;
    }

    /**
     * コマンドを追加
     *
     * @param class-string|object ...$commands
     * @return self
     */
    public function add(string|object ...$commands): self
    {
        // 重複を削除して配列をマージする
        $this->commands = array_unique(
            [
                ...$this->commands,
                ...$commands,
            ],
        );

        return $this;
    }
}
