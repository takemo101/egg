<?php

namespace Takemo101\Egg\Support\Filesystem;

/**
 * パス加工の支援クラス
 */
final class PathHelper
{
    /**
     * constructor
     *
     * @param string $separator
     */
    public function __construct(
        public readonly string $separator = DIRECTORY_SEPARATOR,
    ) {
        //
    }

    /**
     * パスの結合
     *
     * @param string ...$args
     * @return string
     */
    public function join(string ...$args): string
    {
        if (count($args) === 0) {
            return '';
        }

        $first = $args[0];

        $components = [];
        foreach ($args as $component) {
            $components = [
                ...$components,
                ...array_filter(explode(empty($this->separator) ? DIRECTORY_SEPARATOR : $this->separator, $component))
            ];
        }

        $result = $this->normalize($components);

        $result = implode($this->separator, $result);

        if (strpos($first, $this->separator) === 0) {
            $result = empty($this->separator) ? DIRECTORY_SEPARATOR : $this->separator . $result;
        }

        return $result;
    }

    /**
     * パスを各階層ごとの配列に分離して返す
     *
     * @param string $path
     * @return string[]
     */
    public function split(string $path): array
    {
        return $this->normalize(
            array_filter(explode(empty($this->separator) ? DIRECTORY_SEPARATOR : $this->separator, $path))
        );
    }

    /**
     * パスとして不必要な文字をトリミング
     *
     * @param string $path
     * @return string
     */
    public function trim(string $path): string
    {
        $path = str_replace(' ', '', $path);
        return trim($path, $this->separator);
    }

    /**
     * 結合可能な配列に整える
     *
     * @param string[] $components
     * @return string[]
     */
    private function normalize(array $components): array
    {
        $result = [];
        foreach ($components as $key => $component) {
            $trim = $key == 0 ? rtrim($component, $this->separator) : $this->trim($component);
            if (strlen($trim) == 0) {
                continue;
            }

            $result[] = $trim;
        }

        return $result;
    }
}
