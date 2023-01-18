<?php

namespace Takemo101\Egg\Routing\Shared;

/**
 * @immutable
 */
final class Path implements Equatable
{
    /**
     * @var string
     */
    public readonly string $path;

    /**
     * constructor
     *
     * @param string $path
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $path = '',
    ) {
        $path = StringHelper::trimPathSeparator($path);

        $this->path = $path;
    }

    /**
     * パスを結合する
     *
     * @param self $path
     * @return self
     */
    public function join(self $path): self
    {
        return new self($this->path . StringHelper::PathSeparator . $path->path);
    }

    /**
     * 空かどうか？
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->path);
    }

    /**
     * 等しいか？
     *
     * @param static $other
     * @return boolean
     */
    public function equals(object $other): bool
    {
        return $other instanceof static
            && $this->path === $other->path;
    }

    /**
     * 空のインスタンスを生成
     *
     * @return self
     */
    public static function empty(): self
    {
        return new self();
    }
}
