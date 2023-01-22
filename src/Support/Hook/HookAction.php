<?php

namespace Takemo101\Egg\Support\Hook;

use RuntimeException;

/**
 * フックアクション
 */
final class HookAction
{
    /**
     * @var string
     */
    public readonly string $key;

    public function __construct(
        public readonly HookFunction $function,
    ) {
        $this->key = $this->createUniqueKey($function);
    }

    /**
     * callableな値からキーの生成
     *
     * @param HookFunction $function
     * @return string
     */
    private function createUniqueKey(HookFunction $function): string
    {
        if ($function->isString()) {
            return $function->toString();
        }

        if ($function->isObject()) {
            return spl_object_hash($function->toObject());
        }

        if (!$function->isArray()) {
            throw new RuntimeException('error: invalid function');
        }

        $function = $function->toArray();

        return (is_object($function[0])
            ? spl_object_hash($function[0])
            : $function[0]
        ) . $function[1];
    }

    /**
     * callableな値から生成する
     *
     * @param object|array|string $callable
     * @return self
     */
    public static function fromCallable(object|array|string $callable): self
    {
        return new self(
            new HookFunction($callable),
        );
    }
}
