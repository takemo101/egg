<?php

namespace Takemo101\Egg\Support\Hook;

use RuntimeException;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Shared\CallableCreator;
use ReflectionFunction;
use ReflectionNamedType;
use Closure;

/**
 * フック
 */
final class Hook
{
    /**
     * constructor
     *
     * @param CallableCreator $creator
     * @param array<string,HookFilters> $filters
     */
    public function __construct(
        private readonly CallableCreator $creator,
        private array $filters = []
    ) {
        //
    }

    /**
     * フックの追加
     *
     * @param string $tag
     * @param object|mixed[]|string $function
     * @param integer $priority
     * @return self
     */
    public function on(
        string $tag,
        object|array|string $function,
        int $priority = HookFilter::DefaultPriority,
    ): self {
        if (isset($this->filters[$tag])) {
            $this->filters[$tag]->add(
                HookFilter::fromCallable(
                    priority: $priority,
                    function: $function,
                ),
            );
        } else {
            $this->filters[$tag] = new HookFilters(
                HookFilter::fromCallable(
                    priority: $priority,
                    function: $function,
                ),
            );
        }

        return $this;
    }

    /**
     * 関数の引数を解析してフックを追加する
     *
     * @param Closure $function
     * @param integer $priority
     * @return self
     * @throws RuntimeException
     */
    public function onByType(
        Closure $function,
        int $priority = HookFilter::DefaultPriority,
    ): self {
        $parameters = (new ReflectionFunction($function))
            ->getParameters();

        if (count($parameters) !== 1) {
            throw new RuntimeException('error: invalid function parameter');
        }

        $parameter = $parameters[0];

        $type = $parameter->getType();

        if (!($type instanceof ReflectionNamedType)) {
            throw new RuntimeException('error: invalid function parameter type');
        }

        return $this->on(
            tag: match (true) {
                $type->isBuiltin() => $parameter->getName(),
                default => $type->getName(),
            },
            function: $function,
            priority: $priority,
        );
    }


    /**
     * フックの削除
     *
     * @param string $tag
     * @param object|mixed[]|string $function
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
        if (!isset($this->filters[$tag])) {
            return $parameter;
        }

        $result = $parameter;

        $filters = $this->filters[$tag];

        foreach ($filters->all() as $filter) {
            foreach ($filter->actions() as $action) {
                $callable = $this->creator->create($action->function);

                $result = call_user_func_array(
                    $callable,
                    // フィルタ出力がnullの場合は初期パラメータを渡す
                    [$result ?? $parameter],
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
        if (!isset($this->filters[$tag])) {
            throw new RuntimeException("{$tag} is not registered");
        }

        $filters = $this->filters[$tag];

        foreach ($filters->all() as $filter) {
            foreach ($filter->actions() as $action) {
                $callable = $this->creator->create($action->function);

                call_user_func_array(
                    $callable,
                    [$parameter],
                );
            }
        }
    }
}
