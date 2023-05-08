<?php

namespace Takemo101\Egg\Module;

final class HelperModule extends Module
{
    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void
    {
        // ヘルパーを読み込む
        require $this->app
            ->path
            ->getSettingPath('helper.php');
    }
}
