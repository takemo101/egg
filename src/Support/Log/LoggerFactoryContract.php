<?php

namespace Takemo101\Egg\Support\Log;

use Psr\Log\LoggerInterface;

/**
 * ロガーファクトリ
 */
interface LoggerFactoryContract
{
    /**
     * ロガー作成
     *
     * @param string $key
     * @return LoggerInterface
     */
    public function create(string $key): LoggerInterface;
}
