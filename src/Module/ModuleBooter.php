<?php

namespace Takemo101\Egg\Module;

use RuntimeException;
use LogicException;

final class ModuleBooter
{
    /**
     * constructor
     *
     * @param Modules $modules
     * @param ModuleResolver $resolver
     */
    public function __construct(
        private readonly Modules $modules,
        private readonly ModuleResolver $resolver,
    ) {
        //
    }

    /**
     * module boot
     *
     * @throws RuntimeException|LogicException
     */
    public function boot(): void
    {
        foreach ($this->modules->classes as $module) {
            $resolved = $this->resolver->resolve($module);
            if (!$resolved) {
                $name = $this->getClassName($module);
                throw new RuntimeException("{$name} is not command class");
            }

            $resolved->boot();
        }
    }

    /**
     * クラス名を取得する
     *
     * @param string|object $class
     * @return string
     */
    private function getClassName(string|object $class): string
    {
        return is_object($class) ? get_class($class) : $class;
    }
}
