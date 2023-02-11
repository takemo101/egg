<?php

namespace Takemo101\Egg\Http;

use Takemo101\Egg\Routing\Shared\Filters;

/**
 * environment
 */
final class RootFilters
{
    /**
     * @var Filters
     */
    private Filters $filters;

    /**
     * constructor
     *
     * @param Filters|null $filters
     */
    public function __construct(
        ?Filters $filters = null,
    ) {
        $this->filters = $filters ?? Filters::empty();
    }

    /**
     * フィルターを追加する
     *
     * @param class-string|object ...$filters
     * @return self
     */
    public function add(string|object ...$filters): self
    {
        $this->filters = $this->filters->join(
            Filters::fromPrimitives(...$filters),
        );

        return $this;
    }

    /**
     * フィルターを取得する
     *
     * @return Filters
     */
    public function filters(): Filters
    {
        return $this->filters;
    }
}
