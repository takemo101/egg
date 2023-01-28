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
     * @param string $basePath ベースフルパス
     * @param string $settingPath 設定ディレクトリのベースからの相対パス
     * @param string $configPath コンフィグディレクトリのベースからの相対パス
     * @param string $storagePath ストレージディレクトリのベースからの相対パス
     * @param string|null $dotenv dotenvファイルのベースからの相対パス（ファイル名を含む）
     */
    public function __construct(
        public readonly string $basePath,
        public readonly string $settingPath = 'setting',
        public readonly string $configPath = 'config',
        public readonly string $storagePath = 'storage',
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
    public function basePath(?string $path = null): string
    {
        return $path
            ? $this->helper->join($this->basePath, $path)
            : $this->basePath;
    }

    /**
     * 設定パス取得
     *
     * @param string|null $path
     * @return string
     */
    public function settingPath(?string $path = null): string
    {
        return $path
            ? $this->helper->join($this->basePath, $this->settingPath, $path)
            : $this->helper->join($this->basePath, $this->settingPath);
    }

    /**
     * コンフィグパス取得
     *
     * @param string|null $path
     * @return string
     */
    public function configPath(?string $path = null): string
    {
        return $path
            ? $this->helper->join($this->basePath, $this->configPath, $path)
            : $this->helper->join($this->basePath, $this->configPath);
    }

    /**
     * ストレージパス取得
     *
     * @param string|null $path
     * @return string
     */
    public function storagePath(?string $path = null): string
    {
        return $path
            ? $this->helper->join($this->basePath, $this->storagePath, $path)
            : $this->helper->join($this->basePath, $this->storagePath);
    }
}
