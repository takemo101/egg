<?php

namespace Takemo101\Egg\Support\Config;

use ArrayAccess;

/**
 * コンフィグ
 *
 * @extends ArrayAccess<string,mixed>
 */
interface ConfigRepositoryContract extends ArrayAccess
{
    /**
     * キーに対するパスをコンフィグに設定する
     * キーを指定しない場合はファイル名がキーとなる
     *
     * @param string $key
     * @param string $path
     * @return void
     */
    public function setPath(string $key, string $path): void;

    /**
     * キーに対するコンフィグが存在するか
     *
     * @param string $key
     * @return boolean
     */
    public function hasKey(string $key): bool;

    /**
     * ディレクトリーからコンフィグを設定する
     *
     * @param string $directory
     * @return void
     */
    public function load(string $directory): void;

    /**
     * データを取得
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * コンフィグデータをセット
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * データの存在チェック
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool;
}
