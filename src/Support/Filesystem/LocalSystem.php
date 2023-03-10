<?php

namespace Takemo101\Egg\Support\Filesystem;

use ErrorException;

/**
 * ローカルファイルシステム
 */
final class LocalSystem implements LocalSystemContract
{
    /**
     * @var PathHelper
     */
    public readonly PathHelper $helper;

    /**
     * constructor
     *
     * @param PathHelper|null $helper
     */
    public function __construct(
        ?PathHelper $helper = null,
    ) {
        $this->helper = $helper ?? new PathHelper();
    }

    /**
     * パスヘルパーを取得する
     *
     * @return PathHelper
     */
    public function helper(): PathHelper
    {
        return $this->helper;
    }

    /**
     *  ファイル存在
     *
     * @param string $path
     * @return boolean
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * ファイル読み込み
     *
     * @param string $path
     * @throws LocalSystemException
     * @return null|string
     */
    public function read(string $path): ?string
    {
        if ($this->isFile($path)) {
            $content = file_get_contents($path);
            return $content === false ? null : $content;
        }

        throw new LocalSystemException("does not exist at path [{$path}]");
    }

    /**
     * ファイル書き込み（上書き）
     *
     * @param string $path
     * @param string|resource $content
     * @return boolean
     */
    public function write(string $path, $content): bool
    {
        return (bool)file_put_contents($path, $content);
    }

    /**
     * ファイル追加書き込み（先頭へ）
     *
     * @param string $path
     * @param string $content
     * @return boolean
     */
    public function prepend(string $path, string $content): bool
    {
        if ($this->exists($path)) {
            return $this->write($path, $content . $this->read($path));
        }

        return $this->write($path, $content);
    }

    /**
     * ファイル追加書き込み（最後へ）
     *
     * @param string $path
     * @param string $content
     * @return boolean
     */
    public function append(string $path, string $content): bool
    {
        return (bool)file_put_contents($path, $content, FILE_APPEND);
    }

    /**
     * ファイル削除
     *
     * @param string $path
     * @return boolean
     */
    public function delete(string $path): bool
    {
        try {
            $result = @unlink($path);
        } catch (ErrorException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * ファイル権限
     *
     * @param string $path
     * @param integer $permission
     * @return boolean
     */
    public function chmod(string $path, int $permission = 0o755): bool
    {
        return chmod($path, $permission);
    }

    /**
     * ファイルコピー
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function copy(string $from, string $to): bool
    {
        return copy($from, $to);
    }

    /**
     * ファイル移動
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function move(string $from, string $to): bool
    {
        return rename($from, $to);
    }

    /**
     * シンボリックリンク
     *
     * @param string $target
     * @param string $link
     * @return boolean
     */
    public function symlink(string $target, string $link): bool
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return false;
        }

        return symlink($target, $link);
    }

    /**
     * シンボリックリンクのリンク先を取得
     *
     * @param string $path
     * @throws LocalSystemException
     * @return null|string
     */
    public function readlink(string $path): ?string
    {
        if ($this->exists($path) && $this->isLink($path)) {
            $link = readlink($path);

            return $link ? $link : null;
        }

        throw new LocalSystemException("does not exist or link at path [{$path}]");
    }

    /**
     * 正規化されたパスを返す
     *
     * @param string $path
     * @return null|string
     */
    public function realpath(string $path): ?string
    {
        $path = realpath($path);

        return $path ? $path : null;
    }

    /**
     * ファイルサイズ
     *
     * @param string $path
     * @return integer
     * @throws LocalSystemException
     */
    public function size(string $path): int
    {
        if ($size = filesize($path)) {
            return $size;
        }

        throw new LocalSystemException("does not exist at path [{$path}]");
    }

    /**
     * ファイルタイム
     *
     * @param string $path
     * @return integer
     */
    public function time(string $path): int
    {
        if ($time = filemtime($path)) {
            return $time;
        }

        throw new LocalSystemException("does not exist at path [{$path}]");
    }

    /**
     * ファイルか
     *
     * @param string $path
     * @return boolean
     */
    public function isFile(string $path): bool
    {
        return is_file($path);
    }

    /**
     * ディレクトリーか
     *
     * @param string $path
     * @return boolean
     */
    public function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * リンクか
     *
     * @param string $path
     * @return boolean
     */
    public function isLink(string $path): bool
    {
        return is_link($path);
    }

    /**
     * 読み込み可能か
     *
     * @param string $path
     * @return boolean
     */
    public function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * 書き込み可能か
     *
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * ファイル情報抽出
     *
     * @param string $path
     * @param integer $option
     * @throws LocalSystemException
     * @return string|array{dirname?:string,basename:string,extension?:string,filename:string}
     */
    public function extract(string $path, int $option = PATHINFO_BASENAME): string|array
    {
        return pathinfo($path, $option);
    }

    /**
     * ファイルパーミッション取得
     *
     * @param string $path
     * @return null|integer
     */
    public function permission(string $path): ?int
    {
        $result = fileperms($path);

        return $result === false ? null : $result;
    }

    /**
     * ファイルタイプ取得
     *
     * @param string $path
     * @return null|string
     */
    public function type(string $path): ?string
    {
        $result = filetype($path);

        return $result === false ? null : $result;
    }

    /**
     * ファイルMimeタイプ取得
     *
     * @param string $path
     * @return null|string
     */
    public function mimeType(string $path): ?string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            return null;
        }

        $result = finfo_file($finfo, $path);

        return $result === false ? null : $result;
    }

    /**
     * パスを捜索
     *
     * @param string $pattern
     * @return null|string[]
     */
    public function glob(string $pattern): ?array
    {
        $result = glob($pattern);

        return $result === false ? null : $result;
    }

    /**
     * ディレクトリ作成
     *
     * @param string $path
     * @param integer $permission
     * @param boolean $recursive
     * @return boolean
     */
    public function makeDirectory(string $path, int $permission = 0o755, bool $recursive = true): bool
    {
        return mkdir($path, $permission, $recursive);
    }

    /**
     * ディレクトリ移動
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function moveDirectory(string $from, string $to): bool
    {
        return @rename($from, $to) === true;
    }

    /**
     * ディレクトリコピー
     *
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function copyDirectory(string $from, string $to): bool
    {
        if (!$this->isDirectory($from)) {
            return false;
        }

        $this->makeDirectory($to, 0o777);

        $paths = $this->glob($this->helper->join($from, "*"));

        foreach ($paths as $path) {
            /** @var string */
            $target = $this->extract($path);

            $target = $this->helper->join($to, $target);

            if ($this->isDirectory($path)) {
                return $this->copyDirectory($path, $target);
            }

            if (!$this->copy($path, $target)) {
                return false;
            }
        }

        return true;
    }

    /**
     * ディレクトリ削除
     *
     * @param string $path
     * @param boolean $keep
     * @return boolean
     */
    public function deleteDirectory(string $path, bool $keep = true): bool
    {
        if (!$this->isDirectory($path)) {
            return false;
        }

        $paths = $this->glob($this->helper->join($path, "*"));

        foreach ($paths as $target) {
            if ($this->isDirectory($target)) {
                if (!$this->deleteDirectory($target, $keep)) {
                    return false;
                }
            } elseif (!$this->delete($target)) {
                return false;
            }
        }

        if (!$keep) {
            rmdir($path);
        }

        return true;
    }
}
