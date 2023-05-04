<?php

namespace Takemo101\Egg\Kernel;

use Takemo101\Egg\Support\Filesystem\PathHelper;

/**
 * application path setting
 */
final class ApplicationPath
{
    private readonly PathHelper $helper;

    /**
     * constructor
     *
     * @param string $base ベースフルパス
     * @param string $setting 設定ディレクトリのベースからの相対パス
     * @param string $config コンフィグディレクトリのベースからの相対パス
     * @param string $storage ストレージディレクトリのベースからの相対パス
     * @param string|null $dotenv dotenvファイルのベースからの相対パス（ファイル名を含む）
     */
    public function __construct(
        public readonly string $base,
        public readonly string $setting = 'setting',
        public readonly string $config = 'config',
        public readonly string $storage = 'storage',
        public readonly ?string $dotenv = null,
    ) {
        $this->helper = new PathHelper();
    }

    /**
     * ベースパス取得
     *
     * @param string|null $path
     * @return string
     */
    public function getBasePath(?string $path = null): string
    {
        return $path
            ? $this->helper->join($this->base, $path)
            : $this->base;
    }

    /**
     * 設定パス取得
     *
     * @param string|null $path
     * @return string
     */
    public function getSettingPath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->setting, $path]
            : [$this->setting];

        return $this->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }

    /**
     * コンフィグパス取得
     *
     * @param string|null $path
     * @return string
     */
    public function getConfigPath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->config, $path]
            : [$this->config];

        return $this->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }

    /**
     * ストレージパス取得
     *
     * @param string|null $path
     * @return string
     */
    public function getStoragePath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->storage, $path]
            : [$this->storage];

        return $this->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }
}
