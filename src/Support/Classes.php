<?php

namespace Takemo101\Egg\Support;

/**
 * クラスのコレクション
 */
abstract class Classes
{
    /** @var array<class-string|object> */
    public array $classes;

    /**
     * constructor
     *
     * @param class-string|object ...$classes
     */
    public function __construct(
        string|object ...$classes,
    ) {
        $this->classes = $classes;
    }

    /**
     * クラスを追加
     *
     * @param class-string|object ...$classes
     * @return self
     */
    public function add(string|object ...$classes): self
    {
        /** @var array<class-string|object> */
        $tempClasses = [
            ...$this->classes,
            ...$classes,
        ];

        $this->classes = $tempClasses;

        return $this;
    }

    /**
     * 空のインスタンスを返す
     *
     * @return static
     */
    public static function empty(): static
    {
        return new static();
    }
}
