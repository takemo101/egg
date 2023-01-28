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
     * パスをコンフィグに設定する
     * キーを指定しない場合はファイル名がキーとなる
     *
     * @param string $key
     * @param string $path
     * @return void
     */
    public function setLoadPath(string $key, string $path): void;

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
}
