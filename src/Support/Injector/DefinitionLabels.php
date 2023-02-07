<?php

namespace Takemo101\Egg\Support\Injector;

/**
 * 依存定義のラベルコレクション
 */
final class DefinitionLabels
{
    /**
     * @var string[]
     */
    public readonly array $labels;

    public function __construct(
        string ...$labels,
    ) {
        $this->labels = array_unique($labels);
    }

    /**
     * ラベルを追加する
     *
     * @param string ...$labels
     * @return self
     */
    public function add(string ...$labels): self
    {
        return new self(...[
            ...$this->labels,
            ...$labels,
        ]);
    }

    /**
     * ラベルを削除する
     *
     * @param string ...$labels
     * @return self
     */
    public function remove(string ...$labels): self
    {
        foreach ($labels as $search) {
            foreach ($this->labels as $index => $label) {
                if ($search === $label) {
                    unset($this->labels[$index]);
                }
            }
        }

        return $this;
    }
}
