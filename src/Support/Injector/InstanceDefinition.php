<?php

namespace Takemo101\Egg\Support\Injector;

/**
 * 依存注入するための定義
 */
final class InstanceDefinition implements DefinitionContract
{
    /**
     * construct
     *
     * @param mixed $instance
     */
    public function __construct(
        private readonly mixed $instance,
    ) {
        //
    }

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
    ) {
        return $this->instance;
    }
}
