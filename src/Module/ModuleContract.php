<?php

namespace Takemo101\Egg\Module;

/**
 * モジュールに必要な依存関係を定義したり
 * 起動処理を実行する
 */
interface ModuleContract
{
    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void;
}
