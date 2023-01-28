<?php

namespace Takemo101\Egg\Support\Hook;

use Takemo101\Egg\Support\Injector\DefinitionContract;
use Takemo101\Egg\Support\Injector\DefinitionDataFilterContract;

final class HookDefinitionDataFilter implements DefinitionDataFilterContract
{
    /**
     * constructor
     *
     * @param Hook $hook
     */
    public function __construct(
        private readonly Hook $hook,
    ) {
        //
    }

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
    ) {
        $data = $this->hook->applyFilter($label, $data);

        $definition->update($data);

        return $data;
    }
}
