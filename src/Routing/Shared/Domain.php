<?php

namespace Takemo101\Egg\Routing\Shared;

use InvalidArgumentException;

/**
 * @immutable
 */
final class Domain implements Equatable
{
    /**
     * @var string
     */
    public readonly string $domain;

    /**
     * constructor
     *
     * @param string $domain
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $domain,
    ) {
        // ドメインを取得して空かどうか検証する
        $domain = StringHelper::trimSeparator($domain);
        if (empty($domain)) throw new InvalidArgumentException('error: domain is empty!');

        $this->domain = $domain;
    }

    /**
     * URN文字列に変換
     *
     * @param Path $path
     * @return string
     */
    public function toURNString(Path $path): string
    {
        $domain = $this->domain . StringHelper::PathSeparator;
        return $path->isEmpty()
            ? $domain
            : $domain . $path->path;
    }

    /**
     * ドメインを結合する
     *
     * @param self $domain
     * @return self
     */
    public function join(self $domain): self
    {
        return new self($this->domain . StringHelper::DomainSeparator . $domain->domain);
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
            && $this->domain === $other->domain;
    }

    /**
     * URI文字列からインスタンスを生成
     *
     * @param string $uri
     * @return self
     */
    public static function fromURIString(string $uri): self
    {
        $uri = StringHelper::trimProtocol($uri);
        $uri = StringHelper::trimPath($uri);

        return new self($uri);
    }
}
