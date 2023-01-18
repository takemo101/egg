<?php

namespace Takemo101\Egg\Support\Filesystem;

/**
 * ローカルストレージのファイルシステム操作
 */
interface LocalSystemContract
{
    /**
     * ファイル存在
     *
     * @param string $path
     * @return boolean
     */
    public function exists(string $path): bool;

    /**
     * ファイル読み込み
     *
     * @param string $path
     * @return null|string
     */
    public function read(string $path): ?string;

    /**
     * ファイル書き込み（上書き）
     *
     * @param string $path
     * @param string|resource $content
     * @return boolean
     */
    public function write(string $path, $content): bool;

    /**
     * ファイル追加書き込み（先頭へ）
     *
     * @param string $path
     * @param string $content
     * @return boolean
     */
    public function prepend(string $path, string $content): bool;

    /**
     * ファイル追加書き込み（最後へ）
     *
     * @param string $path
     * @param string $content
     * @return boolean
     */
    public function append(string $path, string $content): bool;

    /**
     * ファイル削除
     *
     * @param string $path
     * @return boolean
     */
    public function delete(string $path): bool;

    /**
     * ファイル権限
     *
     * @param string $path
     * @param integer $permission
     * @return boolean
     */
    public function chmod(string $path, int $permission = 0755): bool;

    /**
     * ファイルコピー
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function copy(string $from, string $to): bool;

    /**
     * ファイル移動
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function move(string $from, string $to): bool;

    /**
     * シンボリックリンク
     *
     * @param string $target
     * @param string $link
     * @return boolean
     */
    public function symlink(string $target, string $link): bool;

    /**
     * シンボリックリンクのリンク先を取得
     *
     * @param string $path
     * @throws LocalSystemException
     * @return null|string
     */
    public function readlink(string $path): ?string;

    /**
     * 正規化されたパスを返す
     *
     * @param string $path
     * @return string
     */
    public function realpath(string $path): string;

    /**
     * ファイルサイズ
     *
     * @param string $path
     * @return integer
     */
    public function size(string $path): int;

    /**
     * ファイルタイム
     *
     * @param string $path
     * @return integer
     */
    public function time(string $path): int;

    /**
     * ファイルか
     *
     * @param string $path
     * @return boolean
     */
    public function isFile(string $path): bool;

    /**
     * ディレクトリーか
     *
     * @param string $path
     * @return boolean
     */
    public function isDirectory(string $path): bool;

    /**
     * リンクか
     *
     * @param string $path
     * @return boolean
     */
    public function isLink(string $path): bool;

    /**
     * 読み込み可能か
     *
     * @param string $path
     * @return boolean
     */
    public function isReadable(string $path): bool;

    /**
     * 書き込み可能か
     *
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path): bool;

    /**
     * ファイル情報抽出
     *
     * @param string $path
     * @param ExtractType $option
     * @return string
     */
    public function extract(string $path, ExtractType $option = ExtractType::Basename): string;

    /**
     * ファイルパーミッション取得
     *
     * @param string $path
     * @return null|integer
     */
    public function permission(string $path): ?int;

    /**
     * ファイルタイプ取得
     *
     * @param string $path
     * @return null|string
     */
    public function type(string $path): ?string;

    /**
     * ファイルMimeタイプ取得
     *
     * @param string $path
     * @return null|string
     */
    public function mimeType(string $path): ?string;

    /**
     * パスを捜索
     *
     * @param string $pattern
     * @return null|array
     */
    public function glob(string $pattern): ?array;

    /**
     * ディレクトリ作成
     *
     * @param string $path
     * @param integer $permission
     * @param boolean $recursive
     * @return boolean
     */
    public function makeDirectory(string $path, int $permission = 0755, bool $recursive = true): bool;

    /**
     * ディレクトリ移動
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function moveDirectory(string $from, string $to): bool;

    /**
     * ディレクトリコピー
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function copyDirectory(string $from, string $to): bool;

    /**
     * ディレクトリ削除
     *
     * @param string $path
     * @param boolean $keep
     * @return boolean
     */
    public function deleteDirectory(string $path, bool $keep = false): bool;
}
