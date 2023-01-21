<?php

namespace Takemo101\Egg\Kernel;

/**
 * application environment setting
 */
final class ApplicationEnvironment
{
    /**
     * @var string
     */
    public readonly string $environment;

    /**
     * constructor
     *
     * @param string $environment
     * @param boolean $debug
     */
    public function __construct(
        string $environment,
        public readonly bool $debug,
    ) {
        $this->environment = strtolower($environment);
    }

    /**
     * 環境設定の一致確認
     *
     * @param string $environment
     * @return boolean
     */
    public function is(string $environment): bool
    {
        return $this->environment === strtolower($environment);
    }
}
