<?php

namespace Takemo101\Egg\Support\Log;

use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * ロガーのコレクション
 */
final class Loggers
{
    /**
     * @var array<string,LoggerFactoryContract>
     */
    private array $factories;

    /**
     * @var array<string,LoggerInterface> $factories
     */
    private array $loggers = [];

    /**
     * constructor
     *
     * @param array<string,LoggerFactoryContract> $factories
     */
    public function __construct(
        array $factories = [],
    ) {
        foreach ($factories as $key => $factory) {
            $this->addFactory($key, $factory);
        }
    }

    /**
     * ファクトリを追加
     *
     * @param string $key
     * @param LoggerFactoryContract $factory
     * @return self
     */
    public function addFactory(string $key, LoggerFactoryContract $factory): self
    {
        $this->factories[$key] = $factory;

        return $this;
    }

    /**
     * キーが一致するロガーを取得する
     *
     * @param string $key
     * @return LoggerInterface
     * @throws RuntimeException
     */
    public function get(string $key): LoggerInterface
    {
        if (isset($this->loggers[$key])) return $this->loggers[$key];

        if (!isset($this->factories[$key]))
            throw new RuntimeException("error: [key={$key}] is not exists!");

        $logger = $this->factories[$key]->create($key);

        $this->loggers[$key] = $logger;

        return $logger;
    }
}
