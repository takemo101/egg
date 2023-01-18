<?php

namespace Takemo101\Egg\Routing\Shared;

use Closure;
use InvalidArgumentException;
use RuntimeException;

/**
 * @immutable
 */
final class Handler
{
    /**
     * constructor
     *
     * @param mixed $handler
     * @throws RuntimeException
     */
    public function __construct(
        public readonly mixed $handler,
    ) {
        if (!($this->isObject()
            || $this->isArray()
            || $this->isString()
        )) {
            throw new InvalidArgumentException('error: handler is not object, array or string!');
        }
    }

    /**
     * Closureかどうか？
     *
     * @return boolean
     */
    public function isClosure(): bool
    {
        return $this->isObject()
            && ($this->handler instanceof Closure);
    }

    /**
     * Objectかどうか？
     *
     * @return boolean
     */
    public function isObject(): bool
    {
        return is_object($this->handler);
    }

    /**
     * 配列かどうか？
     *
     * @return boolean
     */
    public function isArray(): bool
    {
        return is_array($this->handler);
    }

    /**
     * 文字列かどうか？
     *
     * @return boolean
     */
    public function isString(): bool
    {
        return is_string($this->handler);
    }

    /**
     * callableかどうか？
     *
     * @return boolean
     */
    public function isCallable(): bool
    {
        return is_callable($this->handler);
    }

    /**
     * Closureに変換
     *
     * @return Closure
     * @throws RuntimeException
     */
    public function toClosure(): Closure
    {
        $handler = $this->handler;

        if (is_object($handler) && ($handler instanceof Closure)) {
            return $handler;
        }

        throw new RuntimeException('error: handler is not closure!');
    }

    /**
     * Objectに変換
     *
     * @return object
     * @throws RuntimeException
     */
    public function toObject(): object
    {
        $handler = $this->handler;

        if (is_object($handler)) {
            return $handler;
        }

        throw new RuntimeException('error: handler is not object!');
    }

    /**
     * 配列に変換
     *
     * @return array
     * @throws RuntimeException
     */
    public function toArray(): array
    {
        $handler = $this->handler;

        if (is_array($handler)) {
            return $handler;
        }

        throw new RuntimeException('error: handler is not array!');
    }

    /**
     * 文字列に変換
     *
     * @return string
     * @throws RuntimeException
     */
    public function toString(): string
    {
        $handler = $this->handler;

        if (is_string($handler)) {
            return $handler;
        }

        throw new RuntimeException('error: handler is not string!');
    }

    /**
     * callableに変換
     *
     * @return callable
     * @throws RuntimeException
     */
    public function toCallable(): callable
    {
        $handler = $this->handler;

        if (is_callable($handler)) {
            return $handler;
        }

        throw new RuntimeException('error: handler is not callable!');
    }
}
