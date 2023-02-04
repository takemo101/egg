<?php

namespace Takemo101\Egg\Routing\Shared;

use Takemo101\Egg\Support\Shared\Handler;

/**
 * @immutable
 */
class Filters
{
    /**
     * @var Handler[]
     */
    public readonly array $filters;

    /**
     * constructor
     *
     * @param Handler ...$filters
     */
    final public function __construct(
        Handler ...$filters,
    ) {
        $this->filters = $filters;
    }

    /**
     * フィルターにHandlerを追加する
     *
     * @param Handler ...$filters
     * @return self
     */
    public function add(Handler ...$filters): self
    {
        return new self(
            ...$this->filters,
            ...$filters,
        );
    }

    /**
     * フィルターを結合する
     *
     * @param self $filters
     * @return self
     */
    public function join(self $filters): self
    {
        return $this->add(
            ...$filters->filters,
        );
    }

    /**
     * 空かどうか？
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->filters);
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

    /**
     * 値からインスタンスを生成
     *
     * @param object|mixed[]|string ...$primitives
     * @return static
     */
    public static function fromPrimitives(object|array|string ...$primitives): static
    {
        /** @var Handler[] */
        $handlers = [];

        foreach ($primitives as $primitive) {
            $handlers[] = new Handler($primitive);
        }

        return new static(...$handlers);
    }
}
