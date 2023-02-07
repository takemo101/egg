<?php

namespace Takemo101\Egg\Support\Injector;

/**
 * 依存注入定義の解決時のフィルタ処理コレクション
 */
final class DefinitionDataFilters
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
     * @param DefinitionLabels $labels
     * @param DefinitionContract $definition
     * @param mixed $data
     * @return mixed
     */
    public function filter(
        DefinitionLabels $labels,
        DefinitionContract $definition,
        mixed $data,
    ) {
        $result = $data;

        // ラベル毎にフィルタ処理を行う
        foreach ($labels->labels as $label) {
            foreach ($this->filters as $filter) {
                $result = $filter->filter(
                    label: $label,
                    definition: $definition,
                    data: $result,
                );
            }
        }

        return $result;
    }
}
