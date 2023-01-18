<?php

namespace Takemo101\Egg\Support\Arr;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * 配列のイテレート機能
 */
interface IteratorContract extends
    ArrayAccess,
    Countable,
    IteratorAggregate
{
    /**
     * 自身をコピー
     *
     * @return self
     */
    public function clone();

    /**
     * 要素を全て返す
     *
     * @return array
     */
    public function all(): array;

    /**
     * 要素が存在するか？
     *
     * @return boolean
     */
    public function isEmpty(): bool;
}
