<?php

namespace Takemo101\Egg\Routing\Shared;

enum HttpMethod: string
{
    case Get = 'get';
    case Post = 'post';
    case Put = 'put';
    case Delete = 'delete';
    case Options = 'options';
    case Patch = 'patch';
    case Head = 'head';

    /**
     * 文字列から等しいかどうか判定する
     *
     * @param string $string
     * @return boolean
     */
    public function equalString(string $string): bool
    {
        $method = self::tryFrom(
            strtolower(
                trim($string),
            ),
        );

        return $this === $method;
    }

    /**
     * Getに類するメソッドを配列で取得
     *
     * @return self[]
     */
    public static function toGetMethods(): array
    {
        return [self::Get, self::Head];
    }

    /**
     * 文字列からインスタンスを生成する
     *
     * @param string $string
     * @return self
     */
    public static function fromString(string $string): self
    {
        return self::from(
            strtolower(
                trim($string),
            ),
        );
    }
}
