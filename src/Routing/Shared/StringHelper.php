<?php

namespace Takemo101\Egg\Routing\Shared;

use InvalidArgumentException;

/**
 * 文字列ヘルパー
 */
final class StringHelper
{
    /**
     * プロトコルを表す正規表現
     *
     * @var string
     */
    public const ProtocolRegex = '/^([a-zA-Z]{2,20}:\/\/)/';

    /**
     * ドメインを区切る文字列
     *
     * @var string
     */
    public const DomainSeparator = '.';

    /**
     * パスを区切る文字列
     *
     * @var string
     */
    public const PathSeparator = '/';

    /**
     * 文字列からプロトコル部分を削除する
     *
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function trimProtocol(string $string): string
    {
        $replace = preg_replace(self::ProtocolRegex, '', $string);

        if (!is_string($replace)) {
            throw new InvalidArgumentException('error: is not string!');
        }

        return $replace;
    }

    /**
     * 文字列からパス部分を削除する
     *
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function trimPath(string $string): string
    {
        $replace = preg_replace('/\/.*/', '', $string);

        if (!is_string($replace)) {
            throw new InvalidArgumentException('error: is not string!');
        }

        return $replace;
    }

    /**
     * 文字列の前後からパスの区切り文字を削除する
     *
     * @param string $string
     * @return string
     */
    public static function trimPathSeparator(string $string): string
    {
        return trim($string, self::PathSeparator);
    }

    /**
     * 文字列の前後から区切り文字を削除する
     *
     * @param string $string
     * @return string
     */
    public static function trimSeparator(string $string): string
    {
        return trim($string, self::PathSeparator . self::DomainSeparator);
    }

    /**
     * 文字列からプロトコル部分を取得する
     *
     * @param string $string
     * @return ?string
     */
    public static function parseProtocol(string $string): ?string
    {
        if (!preg_match(self::ProtocolRegex, $string, $matches)) {
            return null;
        }

        return $matches[1];
    }
}
