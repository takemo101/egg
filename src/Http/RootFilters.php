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
    private readonly Filters $filters;

    /**
     * constructor
     *
     * @param object|array|string ...$filters
     */
    public function __construct(
        object|array|string ...$filters,
    ) {
        $this->filters = Filters::fromPrimitives(...$filters);
    }

    /**
     * フィルターから全ての作成する
     *
     * @param Filters $filters
     * @return Filters
     */
    public function createHttpFilters(Filters $filters): Filters
    {
        return $this->filters->join($filters);
    }
}
