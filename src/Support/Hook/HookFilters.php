<?php

namespace Takemo101\Egg\Support\Hook;

/**
 * フックフィルタ
 * アクションのコレクションとなる
 */
final class HookFilters
{
    /**
     * @var array<integer,HookFilter>
     */
    private array $filters = [];

    /**
     * constructor
     *
     * @param HookFilter ...$filters
     */
    public function __construct(
        HookFilter ...$filters,
    ) {
        $this->add(...$filters);
    }

    /**
     * フィルタ追加
     *
     * @param HookFilter ...$filters
     * @return self
     */
    public function add(HookFilter ...$filters): self
    {
        foreach ($filters as $filter) {
            $priority = $filter->adjustPriority($this->filters);

            $this->filters[$priority] = $filter;
        }

        ksort($this->filters);

        return $this;
    }

    /**
     * 優先度とcallableな値からフィルタ削除
     *
     * @param integer ...$priority
     * @param object|mixed[]|string $function
     * @return self
     */
    public function remove(int $priority, object|array|string $function): self
    {
        $this->get($priority)?->remove(HookAction::fromCallable($function));

        return $this;
    }

    /**
     * 優先度からフィルタを取得
     *
     * @return HookFilter|null
     */
    public function get(int $priority): ?HookFilter
    {
        return $this->filters[$priority] ?? null;
    }

    /**
     * フィルタを全て取得
     *
     * @return array<integer,HookFilter>
     */
    public function all(): array
    {
        return $this->filters;
    }

    /**
     * フィルタを全てクリア
     *
     * @return self
     */
    public function clear(): self
    {
        $this->filters = [];

        return $this;
    }
}
