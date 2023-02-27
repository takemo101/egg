<?php

namespace Takemo101\Egg\Support\ResourcePublisher;

use Takemo101\Egg\Console\Commands;
use Takemo101\Egg\Module\Module;
use Takemo101\Egg\Support\Filesystem\LocalSystemContract;

/**
 * リソースを公開するモジュール
 */
final class PublishResourceModule extends Module
{
    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->container->singleton(
            ResourcePublisherContract::class,
            fn () => new ResourcePublisher(
                $this->app->container->make(LocalSystemContract::class),
            ),
        );

        $this->app->container->singleton(
            PublishResources::class,
            fn () => new PublishResources(),
        );

        $this->hook()->register(
            Commands::class,
            fn (Commands $commands) => $commands->add(
                PublishResourceCommand::class,
            ),
        );
    }
}
