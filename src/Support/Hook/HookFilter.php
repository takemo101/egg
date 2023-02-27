<?php

namespace Takemo101\Egg\Support\Hook;

use RuntimeException;

// https://github.com/voku/php-hooks/blob/master/src/voku/helper/Hooks.php

/**
 * フックフィルタ
 * アクションのコレクションとなる
 */
final class HookFilter
{
    /**
     * @var integer
     */
    public const DefaultPriority = 50;

    /**
     * @var array<string,HookAction>
     */
    private array $actions = [];

    /**
     * constructor
     *
     * @param integer $priority フィルタの優先度
     * @param HookAction ...$actions
     */
    public function __construct(
        private int $priority = self::DefaultPriority,
        HookAction ...$actions,
    ) {
        $this->add(...$actions);
    }

    /**
     * 優先度の調整
     * 引数に与えた配列に優先度が含まれている場合は、
     * 含まれてない優先度に調整するために、
     * 優先度をインクリメントしていく
     *
     * @param array<integer,mixed> $array 基準となる配列
     * @return integer
     */
    public function adjustPriority(array $array): int
    {
        $priority = $this->priority;

        while (true) {
            if (!isset($array[$priority])) {
                break;
            }
            $priority++;
        }

        return $this->priority = $priority;
    }

    /**
     * アクション追加
     *
     * @param HookAction ...$actions
     * @return self
     */
    public function add(HookAction ...$actions): self
    {
        foreach ($actions as $action) {
            $this->actions[$action->key] = $action;
        }

        return $this;
    }

    /**
     * アクション削除
     *
     * @param HookAction ...$actions
     * @return self
     */
    public function remove(HookAction ...$actions): self
    {
        foreach ($actions as $action) {
            unset($this->actions[$action->key]);
        }

        return $this;
    }

    /**
     * アクションを全てクリア
     *
     * @return self
     */
    public function clear(): self
    {
        $this->actions = [];

        return $this;
    }

    /**
     * getter
     *
     * @return integer
     */
    public function priority(): int
    {
        return $this->priority;
    }

    /**
     * getter
     *
     * @return array<string,HookAction>
     */
    public function actions(): array
    {
        return $this->actions;
    }

    /**
     * callableな値からフィルタを作成
     *
     * @param integer $priority
     * @param object|mixed[]|string $function
     * @return self
     */
    public static function fromCallable(
        int $priority,
        object|array|string $function,
    ): self {
        return new self(
            priority: $priority,
            action: HookAction::fromCallable($function),
        );
    }
}
