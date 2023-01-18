<?php

namespace Takemo101\Egg\Routing\Shared;

/**
 * 等価性を持つオブジェクト
 */
interface Equatable
{
    /**
     * 等しいか？
     *
     * @param static $other
     * @return boolean
     */
    public function equals(object $other): bool;
}
