<?php

namespace Takemo101\Egg\Support\Hook;

use RuntimeException;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Shared\CallableCreator;

/**
 * フック
 */
final class Hook
{
    /**
     * @var CallableCreator
     */
    private readonly CallableCreator $creator;

    /**
     * constructor
     *
     * @param ContainerContract $container
     * @param array<string,HookFilters> $filters
     */
    public function __construct(
        private readonly ContainerContract $container,
        private array $filters = []
    ) {
        $this->creator = new CallableCreator($container);
    }

    /**
     * フックの登録
     *
     * @param string $tag
     * @param object|array|string $function
     * @param integer $priority
     * @return self
     */
    public function register(
        string $tag,
        object|array|string $function,
        int $priority = HookFilter::DefaultPriority,
    ): self {
        if (!isset($this->filters[$tag])) {
            $this->filters[$tag] = new HookFilters(
                HookFilter::fromCallable(
                    priority: $priority,
                    function: $function,
                ),
            );
        }

        $this->filters[$tag]->add(
            HookFilter::fromCallable(
                priority: $priority,
                function: $function,
            ),
        );

        return $this;
    }

    /**
     * フックの削除
     *
     * @param string $tag
     * @param object|array|string $function
     * @param integer $priority
     * @return self
     */
    public function remove(
        string $tag,
        object|array|string $function,
        int $priority = HookFilter::DefaultPriority,
    ): self {
        if (isset($this->filters[$tag])) {
            $this->filters[$tag]->remove(
                priority: $priority,
                function: $function,
            );
        }

        return $this;
    }

    /**
     * タグが登録されているか
     *
     * @param string $tag
     *
     * @return boolean
     */
    public function hasTag(string $tag): bool
    {
        return isset($this->filters[$tag]);
    }

    /**
     * フックを実行する
     *
     * @param string $tag
     * @param mixed $parameter
     * @return mixed
     */
    public function applyFilter(string $tag, $parameter): mixed
    {
        if (!isset($this->filters[$tag])) throw new RuntimeException("{$tag} is not registered");

        $result = $parameter;

        $filters = $this->filters[$tag];

        foreach ($filters->filters as $filter) {
            foreach ($filter->actions as $action) {
                $callable = $this->creator->create($action->function);

                $result = $this->container->call(
                    callable: $callable,
                    // フィルタ出力がnullの場合は初期パラメータを渡す
                    options: [$result ?? $parameter],
                );
            }
        }

        return $result;
    }

    /**
     * フックを実行する
     *
     * @param string $tag
     * @param mixed $parameter
     * @return void
     */
    public function doAction(string $tag, $parameter): void
    {
        if (!isset($this->filters[$tag])) throw new RuntimeException("{$tag} is not registered");

        $filters = $this->filters[$tag];

        foreach ($filters->filters as $filter) {
            foreach ($filter->actions as $action) {
                $callable = $this->creator->create($action->function);

                $this->container->call(
                    callable: $callable,
                    options: [$parameter],
                );
            }
        }
    }
}
