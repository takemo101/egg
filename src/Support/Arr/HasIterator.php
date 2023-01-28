<?php

namespace Takemo101\Egg\Support\Arr;

use ArrayIterator;
use Traversable;

trait HasIterator
{
    /**
     * @var mixed[]
     */
    protected $array = [];

    /**
     * implement IteratorAggregate
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->array);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetUnset($offset): void
    {
        $this->forget($offset);
    }

    /**
     * implement Countable
     */
    public function count(): int
    {
        return count($this->array);
    }

    /**
     * 要素を全て返す
     *
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->array;
    }

    /**
     * 要素が存在するか？
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }
}
