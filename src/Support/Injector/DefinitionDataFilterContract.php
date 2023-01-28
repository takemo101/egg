<?php

namespace Takemo101\Egg\Support\Injector;

/**
 * 依存注入定義の解決時のフィルタ処理
 */
interface DefinitionDataFilterContract
{
    /**
     * 依存注入定義の解決時に
     * 定義されたデータをフィルタ処理する
     *
     * @param string $label
     * @param DefinitionContract $definition
     * @param mixed $data
     * @return mixed
     */
    public function filter(
        string $label,
        DefinitionContract $definition,
        mixed $data,
    );
}
