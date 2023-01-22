<?php

namespace Takemo101\Egg\Kernel;

/**
 * bootstrap
 */
final class Bootstrap
{
    /**
     * @var string[]
     */
    private array $loaders = [
        //
    ];

    /**
     * constructor
     *
     * @param LoaderResolverContract $resolver
     * @param string ...$loaders
     */
    public function __construct(
        private readonly LoaderResolverContract $resolver,
        string ...$loaders,
    ) {
        $this->addLoader(...$loaders);
    }

    /**
     * ローダーの追加
     *
     * @param string ...$loaders
     * @return self
     */
    public function addLoader(string ...$loaders): self
    {
        $this->loaders = array_unique(
            [
                ...$this->loaders,
                ...$loaders,
            ]
        );

        return $this;
    }

    /**
     * ローダーを全て実行
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->loaders as $loader) {
            $this->resolver
                ->resolve($loader)
                ->load();
        }
    }
}
