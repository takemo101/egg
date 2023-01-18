<?php

namespace Takemo101\Egg\Http;

use Takemo101\Egg\Support\Filesystem\URLHelper;

/**
 * url setting
 */
final class URLSetting
{
    private readonly URLHelper $helper;

    /**
     * constructor
     *
     * @param string $baseURL ベースURL
     */
    public function __construct(
        public readonly string $baseURL,
    ) {
        $this->helper = new URLHelper();
    }

    /**
     * ベースパス取得
     *
     * @param string|null $path
     * @return string
     */
    public function url(?string $path = null): string
    {
        return $path
            ? $this->helper->join($this->baseURL, $path)
            : $this->baseURL;
    }
}
