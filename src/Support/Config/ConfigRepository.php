<?php

namespace Takemo101\Egg\Support\Config;

use Takemo101\Egg\Support\Filesystem\LocalSystem;
use Takemo101\Egg\Support\Arr\Arr;
use RuntimeException;

/**
 * config repository
 */
class ConfigRepository implements ConfigRepositoryContract
{
    /**
     * @var string
     */
    const ConfigExt = '.php';

    /**
     * @var array<string,string|mixed[]>
     */
    protected $config = [];

    /**
     * constructor
     *
     * @param LocalSystem $filesystem
     * @param string|null $directory
     */
    public function __construct(
        private readonly LocalSystem $filesystem,
        ?string $directory = null,
    ) {
        if ($directory) {
            $this->load($directory);
        }
    }

    /**
     * パスからキー文字列を生成
     *
     * @param string $path
     * @return string
     */
    private function createKeyStringByPath(string $path): string
    {
        return basename($path, self::ConfigExt);
    }

    /**
     * パスをコンフィグに設定する
     * キーを指定しない場合はファイル名がキーとなる
     *
     * @param string $key
     * @param string $path
     * @return void
     */
    public function setLoadPath(string $key, string $path): void
    {
        $this->config[$key] = $path;
    }

    /**
     * ディレクトリーからコンフィグを設定する
     *
     * @param string $directory
     * @return void
     */
    public function load(string $directory): void
    {
        $ext = self::ConfigExt;

        $paths = $this->filesystem->glob(
            $this->filesystem
                ->helper
                ->join($directory, "*{$ext}"),
        );

        if (empty($paths)) return;

        foreach ($paths as $path) {
            $key = $this->createKeyStringByPath($path);

            $this->config[$key] = $path;
        }
    }

    /**
     * コンフィグデータをロード
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    private function loadData(string $key, $default = null)
    {
        if (isset($this->config[$key])) {
            $result = $this->resolve($this->config[$key]);

            $this->config[$key] = $result;

            return $result;
        }

        return $default;
    }

    /**
     * コンフィグデータを解決
     *
     * @param string|mixed[] $config
     * @return mixed[]
     * @throws RuntimeException
     */
    private function resolve(string|array $config = []): array
    {
        if (!is_string($config)) return $config;

        $result = require $config;

        // 配列ではない場合は
        // コンフィグデータではないのでエラー
        if (!is_array($result))
            throw new RuntimeException("error!: config file must return array!");

        return $result;
    }

    /**
     * データを取得
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        [$first, $last] = Arr::firstDotKey($key);
        $this->loadData($first);

        return Arr::get($this->config, $key, $default);
    }

    /**
     * コンフィグデータをセット
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        [$first, $last] = Arr::firstDotKey($key);
        $this->loadData($first);

        Arr::set($this->config, $key, $value);
    }

    /**
     * データの存在チェック
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        [$first, $last] = Arr::firstDotKey($key);
        $this->loadData($first);

        return Arr::has($this->config, $key);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetSet($offset, $value): void
    {
        $this->set((string)$offset, $value);
    }

    /**
     * impelement ArrayAccess
     */
    public function offsetUnset($offset): void
    {
        // 処理なし
    }
}
