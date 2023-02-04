<?php

namespace Takemo101\Egg\Support\Shared;

use Closure;
use InvalidArgumentException;
use RuntimeException;

/**
 * 関数やコールバックを表す
 */
abstract class Functional
{
    /**
     * constructor
     *
     * @param object|mixed[]|string $callable
     * @throws RuntimeException
     */
    public function __construct(
        public readonly object|array|string $callable,
    ) {
        if (!(
            $this->isObject()
            || $this->isArray()
            || $this->isString()
        )) {
            throw new InvalidArgumentException('error: callable is not object, array or string!');
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
            && ($this->callable instanceof Closure);
    }

    /**
     * Objectかどうか？
     *
     * @return boolean
     */
    public function isObject(): bool
    {
        return is_object($this->callable);
    }

    /**
     * 配列かどうか？
     *
     * @return boolean
     */
    public function isArray(): bool
    {
        return is_array($this->callable);
    }

    /**
     * 文字列かどうか？
     *
     * @return boolean
     */
    public function isString(): bool
    {
        return is_string($this->callable);
    }

    /**
     * callableかどうか？
     *
     * @return boolean
     */
    public function isCallable(): bool
    {
        return is_callable($this->callable);
    }

    /**
     * Closureに変換
     *
     * @return Closure
     * @throws RuntimeException
     */
    public function toClosure(): Closure
    {
        $callable = $this->callable;

        if (is_object($callable) && ($callable instanceof Closure)) {
            return $callable;
        }

        throw new RuntimeException('error: callable is not closure!');
    }

    /**
     * Objectに変換
     *
     * @return object
     * @throws RuntimeException
     */
    public function toObject(): object
    {
        $callable = $this->callable;

        if (is_object($callable)) {
            return $callable;
        }

        throw new RuntimeException('error: callable is not object!');
    }

    /**
     * 配列に変換
     *
     * @return mixed[]
     * @throws RuntimeException
     */
    public function toArray(): array
    {
        $callable = $this->callable;

        if (is_array($callable)) {
            return $callable;
        }

        throw new RuntimeException('error: callable is not array!');
    }

    /**
     * 文字列に変換
     *
     * @return string
     * @throws RuntimeException
     */
    public function toString(): string
    {
        $callable = $this->callable;

        if (is_string($callable)) {
            return $callable;
        }

        throw new RuntimeException('error: callable is not string!');
    }

    /**
     * callableに変換
     *
     * @return callable
     * @throws RuntimeException
     */
    public function toCallable(): callable
    {
        $callable = $this->callable;

        if (is_callable($callable)) {
            return $callable;
        }

        throw new RuntimeException('error: callable is not callable!');
    }
}
