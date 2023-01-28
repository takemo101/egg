<?php

namespace Takemo101\Egg\Support\Injector;

/**
 * 依存注入定義の解決時のフィルタ処理コレクション
 */
final class DefinitionDataFilters implements DefinitionDataFilterContract
{
    /**
     * @var DefinitionDataFilterContract[]
     */
    private array $filters;

    /**
     * construct
     *
     * @param DefinitionDataFilterContract ...$filters
     */
    public function __construct(DefinitionDataFilterContract ...$filters)
    {
        $this->filters = $filters;
    }

    /**
     * フィルタを追加する
     *
     * @param DefinitionDataFilterContract $filter
     * @return self
     */
    public function add(DefinitionDataFilterContract $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * 依存注入定義の解決時に
     * 定義されたデータをフィルタ処理する
     *
     * @param string $label
     * @param DefinitionContract $definition
     * @param mixed $data
     * @return mixed
     */
    public function filter(
        string $label,
        DefinitionContract $definition,
        mixed $data,
    ) {
        $result = $data;

        foreach ($this->filters as $filter) {
            $result = $filter->filter(
                label: $label,
                definition: $definition,
                data: $result,
            );
        }

        return $result;
    }
}
