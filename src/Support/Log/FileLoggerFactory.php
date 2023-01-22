<?php

namespace Takemo101\Egg\Support\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Processor\UidProcessor;
use Takemo101\Egg\Kernel\ApplicationPath;

/**
 * ファイルに出力するタイプのロガーファクトリ
 */
final class FileLoggerFactory implements LoggerFactoryContract
{
    /**
     * constructor
     *
     * @param string $path
     * @param string $filename
     * @param Level $level
     * @param ApplicationPath $applicationPath
     */
    public function __construct(
        private readonly string $path,
        private readonly string $filename,
        private readonly Level $level,
        private readonly ApplicationPath $applicationPath,
    ) {
        //
    }

    /**
     * ロガー作成
     *
     * @return LoggerInterface
     */
    public function create(string $key): LoggerInterface
    {
        $logger = new Logger($key);

        $processor = new UidProcessor();

        $logger->pushProcessor($processor);

        $logger->pushHandler($this->createHandler());

        return $logger;
    }

    /**
     * ファイルハンドラーを作成する
     *
     * @return HandlerInterface
     */
    private function createHandler(): HandlerInterface
    {
        $handler = new RotatingFileHandler(
            filename: $this->createPath(),
            maxFiles: 0,
            level: $this->level,
            bubble: true,
            filePermission: 0777,
        );

        $handler->setFormatter(
            new LineFormatter(
                format: null,
                dateFormat: null,
                allowInlineLineBreaks: true,
                ignoreEmptyContextAndExtra: true,
                includeStacktraces: true,
            ),
        );

        return $handler;
    }

    /**
     * ファイルパスを返す
     *
     * @return string
     */
    protected function createPath(): string
    {
        return $this->applicationPath->storagePath(
            sprintf(
                '%s/%s',
                $this->path,
                $this->filename,
            ),
        );
    }
}
