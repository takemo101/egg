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
    public array $actions = [];

    /**
     * constructor
     *
     * @param integer $priority フィルタの優先度
     * @param HookAction ...$actions
     */
    public function __construct(
        public readonly int $priority = self::DefaultPriority,
        HookAction ...$actions,
    ) {
        $this->add(...$actions);
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
     * callableな値からフィルタを作成
     *
     * @param integer $priority
     * @param object|array|string $function
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
