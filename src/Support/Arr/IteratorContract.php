<?php

namespace Takemo101\Egg\Support\Arr;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * 配列のイテレート機能
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends ArrayAccess<TKey,TValue>
 * @extends IteratorAggregate<TKey,TValue>
 */
interface IteratorContract extends
    ArrayAccess,
    Countable,
    IteratorAggregate
{
    /**
     * 要素を全て返す
     *
     * @return array<string,TValue>
     */
    public function all(): array;

    /**
     * 要素が存在するか？
     *
     * @return boolean
     */
    public function isEmpty(): bool;
}
