<?php

namespace Takemo101\Egg\Module\Default\ResourcePublisher;

use Takemo101\Egg\Console\Commands;
use Takemo101\Egg\Module\ModuleContract;
use Takemo101\Egg\Support\Filesystem\LocalSystemContract;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * リソースを公開するモジュール
 */
final class PublishResourceModule implements ModuleContract
{
    public function __construct(
        private readonly ContainerContract $container,
        private readonly Hook $hook,
    ) {
    }

    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void
    {
        $this->container->singleton(
            ResourcePublisherContract::class,
            fn () => new ResourcePublisher(
                $this->container->make(LocalSystemContract::class),
            ),
        );

        $this->container->singleton(
            PublishResources::class,
            fn () => new PublishResources(),
        );

        $this->hook->register(
            Commands::class,
            fn (Commands $commands) => $commands->add(
                PublishResourceCommand::class,
            ),
        );
    }
}
