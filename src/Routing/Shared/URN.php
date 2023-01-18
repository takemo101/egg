<?php

namespace Takemo101\Egg\Routing\Shared;

/**
 * @immutable
 */
final class URN implements Equatable
{
    /**
     * constructor
     *
     * @param Domain $domain
     * @param Path $path
     */
    public function __construct(
        private readonly Domain $domain,
        private readonly Path $path,
    ) {
        //
    }

    /**
     * URN文字列を取得
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->domain->toURNString($this->path);
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
            && $this->toString() === $other->toString();
    }
}
