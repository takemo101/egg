<?php

namespace Takemo101\Egg\Console;

/**
 * コマンドのコレクション
 */
final class Commands
{
    /** @var array<class-string|object> */
    public array $commands;

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
        /** @var array<class-string|object> */
        $tempCommands = [
            ...$this->commands,
            ...$commands,
        ];

        // 重複を削除して配列をマージする
        $this->commands = $tempCommands;

        return $this;
    }

    /**
     * 空のインスタンスを返す
     *
     * @return self
     */
    public static function empty(): self
    {
        return new self();
    }
}
