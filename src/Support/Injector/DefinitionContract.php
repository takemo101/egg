<?php

namespace Takemo101\Egg\Support\Injector;

use Closure;
use Error;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * 依存注入するための定義
 */
interface DefinitionContract
{
    /**
     * 定義を値に解決
     *
     * @param ObjectResolver $resolver
     * @param array<string,mixed> $options
     * @return mixed
     */
    public function resolve(
        ObjectResolver $resolver,
        array $options = [],
    );
}
