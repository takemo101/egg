<?php

namespace Takemo101\Egg\Support\Arr;

use JsonSerializable;

/**
 * array access class
 */
class ArrAccess implements IteratorContract, JsonSerializable
{
    use HasIterator;

    public const DotSeparator = '.';

    /**
     * construct
     *
     * @param array $array
     */
    public function __construct(
        array $array = []
    ) {
        $this->array = $array;
    }

    /**
     * dot 記法から最初のキーと残りのキーを返す
     *
     * @param string $key
     * @return string[]
     */
    public function firstDotKey(string $key): array
    {
        $firstKey = explode(static::DotSeparator, $key)[0];
        $lastKey = ltrim(ltrim($key, $firstKey), static::DotSeparator);
        return [$firstKey, $lastKey];
    }

    /**
     * ドット記法での配列を返す
     *
     * @return array
     */
    public function dot(): array
    {
        return $this->dotting($this->array);
    }

    /**
     * ドット記法 => 値 を配列に直す
     *
     * @param array $array
     * @return static
     */
    public function undot(array $array): static
    {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * ドット記法での配列を返す
     *
     * @param array $array
     * @param string $prepend
     * @return array
     */
    protected function dotting(array $array, string $prepend = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->dotting($value, $prepend . $key . static::DotSeparator));
            } else {
                $result[$prepend . $key] = $value;
            }
        }

        return $result;
    }

    /**
     * 指定したキーに指定した値を入れる
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function set(string $key, $value): static
    {
        $keys = explode(static::DotSeparator, $key);

        $result = &$this->array;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($result[$key]) || !is_array($result[$key])) {
                $result[$key] = [];
            }

            $result = &$result[$key];
        }

        $result[array_shift($keys)] = $value;

        return $this;
    }

    /**
     * 指定したキーの値が存在するか
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return null !== $this->get($key);
    }

    /**
     * 配列値をドット記法で取得
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */

    public function get(string $key, $default = null)
    {
        return $this->dotget($this->array, $key, $default);
    }

    /**
     * 配列値をドット記法で取得
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function dotget(array $array, string $key, $default = null)
    {
        $result = $array;

        if (isset($result[$key])) {
            return $result[$key];
        }

        if (strpos($key, static::DotSeparator) === false) {
            return $default;
        }

        foreach (explode(static::DotSeparator, $key) as $segment) {
            if (!is_array($result) || !array_key_exists($segment, $result)) {
                return $default;
            }

            $result = $result[$segment];
        }

        return $result;
    }

    /**
     * 指定キーに対する配列値を全て取得
     *
     * @param string $value
     * @param string|null $key
     * @return array
     */
    public function pluck(string $value, ?string $key = null): array
    {
        $result = [];

        foreach ($this->array as $item) {
            $itemValue = $this->dotget($item, $value);

            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = $this->get($item, $key);
                $result[$itemKey] = $itemValue;
            }
        }

        return $result;
    }

    /**
     * 指定したキーの配列を削除する
     *
     * @param string|array $keys
     * @return static
     */
    public function forget($keys): static
    {
        $result = &$this->array;

        foreach ((array)$keys as $key) {
            $parts = explode(static::DotSeparator, $key);

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($result[$part]) && is_array($result[$part])) {
                    $result = $result[$part];
                }
            }

            unset($result[array_shift($parts)]);
        }

        return $this;
    }

    /**
     * 指定したキーを除外した配列を返す
     *
     * @param string|array $keys
     * @return array
     */
    public function except($keys): array
    {
        return array_diff_key(
            $this->array,
            array_flip((array)$keys)
        );
    }

    /**
     * 指定したキーの配列だけを返す
     *
     * @param string|array
     * @return array
     */
    public function only($keys): array
    {
        return array_intersect_key(
            $this->array,
            array_flip((array)$keys)
        );
    }

    /**
     * 指定したキーが見つかった配列だけを返す
     *
     * @param string|array $keys
     * @return array
     */
    public function missing($keys): array
    {
        return array_values(
            array_flip(
                array_diff_key(
                    array_flip(
                        (array)$keys
                    ),
                    $this->array
                )
            )
        );
    }

    /**
     * クエリ文字に変換
     *
     * @return string
     */
    public function query()
    {
        return http_build_query($this->array, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * 配列を全て取得
     *
     * @return array
     */
    public function all(): array
    {
        return $this->array;
    }

    /**
     * 全ての配列を返す
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }


    /**
     * serialize value.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * factory
     *
     * @param array $array
     * @return static
     */
    public static function of(array $array = []): static
    {
        return new static($array);
    }
}
