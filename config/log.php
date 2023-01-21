<?php

/**
 * ログの基本設定
 */

use Monolog\Level;

return [
    // ログを格納するベースディレクトリからの相対パス
    'path' => 'log',

    // ログのファイル名
    'filename' => [

        // 通常ログ
        'app' => 'app.log',

        // エラーログ
        'error' => 'error.log',
    ],

    // ログレベル
    'level' => Level::Debug,
];
