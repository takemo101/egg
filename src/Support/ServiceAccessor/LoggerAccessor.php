<?php

namespace Takemo101\Egg\Support\ServiceAccessor;

/**
 * アプリケーションへのアクセス
 *
 * @method static void emergency(string|\Stringable $message, mixed[] $context = [])
 * @method static void alert(string|\Stringable $message, mixed[] $context = [])
 * @method static void critical(string|\Stringable $message, mixed[] $context = [])
 * @method static void error(string|\Stringable $message, mixed[] $context = [])
 * @method static void warning(string|\Stringable $message, mixed[] $context = [])
 * @method static void notice(string|\Stringable $message, mixed[] $context = [])
 * @method static void info(string|\Stringable $message, mixed[] $context = [])
 * @method static void debug(string|\Stringable $message, mixed[] $context = [])
 * @method static void log(mixed $level, string|\Stringable $message, mixed[] $context = [])
 * @see \Psr\Log\LoggerInterface
 */
final class LoggerAccessor extends ServiceAccessor
{
    /**
     * ServiceLocatorに登録しているキーを取得する
     *
     * @return string
     */
    protected static function getServiceAccessKey(): string
    {
        return 'logger';
    }
}
