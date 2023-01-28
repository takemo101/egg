<?php

namespace Takemo101\Egg\Support\Filesystem;

/**
 * URLの支援クラス
 */
final class URLHelper
{
    /**
     * @var string
     */
    public const PathSeparator = '/';

    /**
     * @var array<string,string>
     */
    private const URLEncodePairs = [
        '%2D' => '-',
        '%5F' => '_',
        '%2E' => '.',
        '%21' => '!',
        '%7E' => '~',
        '%2A' => '*',
        '%27' => "'",
        '%28' => '(',
        '%29' => ')',
        '%3B' => ';',
        '%2C' => ',',
        '%2F' => '/',
        '%3F' => '?',
        '%3A' => ':',
        '%40' => '@',
        '%26' => '&',
        '%3D' => '=',
        '%2B' => '+',
        '%24' => '$',
        '%23' => '#',
        '%5B' => '[',
        '%5D' => ']',
    ];

    /**
     * constructor
     */
    public function __construct()
    {
        //
    }

    /**
     * 文字列がURLかどうか？
     *
     * @param string $url
     * @return boolean
     */
    public function isURL(string $url): bool
    {
        $encoded = $this->encode($url);
        return (bool)filter_var($encoded, FILTER_VALIDATE_URL);
    }

    /**
     * 文字列をURLに適合した形式に変換する
     *
     * @param string $url
     * @return string
     */
    public function encode(string $url): string
    {
        return strtr(rawurlencode($url), self::URLEncodePairs);
    }

    /**
     * URLからパス部分を取得する
     * URL形式でなければ引数をそのまま返す
     *
     * @param string $url
     * @return string
     */
    public function toPath(string $url): string
    {
        /** @var string */
        $parse = parse_url(
            $url,
            PHP_URL_PATH,
        );

        return $this->isURL($url) ?
            ltrim(
                $parse,
                self::PathSeparator,
            ) :
            $url;
    }

    /**
     * URLパスとして結合する
     *
     * @param string ...$paths
     * @return string
     */
    public static function join(string ...$paths): string
    {
        return implode(
            self::PathSeparator,
            array_map(
                fn (string $path) => trim($path, self::PathSeparator),
                $paths,
            ),
        );
    }
}
