<?php

namespace Takemo101\Egg\Routing\Shared;

use InvalidArgumentException;

/**
 * @immutable
 */
final class HttpMethods
{
    /**
     * @var HttpMethod[]
     */
    public readonly array $methods;

    /**
     * constructor
     *
     * @param callable ...$methods
     * @throws InvalidArgumentException
     */
    public function __construct(
        HttpMethod ...$methods,
    ) {
        $tempMethods = [];

        foreach ($methods as $m) {
            if (
                in_array($m, $tempMethods, true)
            ) continue;

            $tempMethods[] = $m;
        }

        if (empty($tempMethods)) throw new InvalidArgumentException('error: methods is empty!');

        $this->methods = $tempMethods;
    }

    /**
     * コレクションにメソッドを追加する
     *
     * @param HttpMethod ...$methods
     * @return self
     */
    public function add(HttpMethod ...$methods): self
    {
        return new self(
            ...$this->methods,
            ...$methods,
        );
    }

    /**
     * コレクションを結合する
     *
     * @param self $methods
     * @return self
     */
    public function join(self $methods): self
    {
        return $this->add(
            ...$methods->methods,
        );
    }

    /**
     * 文字列配列からインスタンスを生成
     *
     * @param string ...$strings
     * @return self
     */
    public static function fromStrings(string ...$strings): self
    {
        /**
         * @var HttpMethod[]
         */
        $methods = [];

        foreach ($strings as $s) {
            $methods[] = HttpMethod::from(strtolower($s));
        }

        return new self(...$methods);
    }
}
