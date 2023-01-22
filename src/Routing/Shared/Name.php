<?php

namespace Takemo101\Egg\Routing\Shared;

use InvalidArgumentException;

/**
 * @immutable
 */
final class Name implements Equatable
{
    /**
     * constructor
     *
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly string $name,
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException('error: name is empty!');
        }
    }

    /**
     * 名前を結合する
     *
     * @param self $name
     * @return self
     */
    public function join(self $name): self
    {
        return new self($this->name . $name->name);
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
            && $this->name === $other->name;
    }
}
