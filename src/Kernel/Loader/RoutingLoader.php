<?php

namespace Takemo101\Egg\Kernel\Loader;

use Takemo101\Egg\Http\URLSetting;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\LoaderContract;
use Takemo101\Egg\Routing\AltoRouter\AltoRouterFactory;
use Takemo101\Egg\Routing\RouteBuilder;
use Takemo101\Egg\Routing\RouterContract;
use Takemo101\Egg\Routing\RouterFactoryContract;
use Takemo101\Egg\Support\Config\ConfigRepositoryContract;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\ServiceLocator;
use Takemo101\Egg\Support\Shared\CallObject;

/**
 * ルーティング関連
 */
final class RoutingLoader implements LoaderContract
{
    /**
     * constructor
     *
     * @param Application $app
     */
    public function __construct(
        private readonly Application $app,
    ) {
        //
    }

    /**
     * ロード処理をする
     *
     * @return void
     */
    public function load(): void
    {
        $this->app->container->singleton(
            RouteBuilder::class,
            fn () => new RouteBuilder(),
        );

        $this->app->container->singleton(
            URLSetting::class,
            function (ContainerContract $container) {
                /** @var ConfigRepositoryContract */
                $repository = $container->make(ConfigRepositoryContract::class);

                /** @var string */
                $baseURL = $repository->get('app.url', 'http://localhost');

                return new URLSetting(baseURL: $baseURL);
            },
        );

        $this->app->container->singleton(
            RouterFactoryContract::class,
            function (ContainerContract $container): RouterFactoryContract {
                /** @var ConfigRepositoryContract */
                $repository = $container->make(ConfigRepositoryContract::class);

                /** @var array<string,string> */
                $matchTypes = $repository->get('app.routing.match-types', []);

                /** @var URLSetting */
                $setting = $container->make(URLSetting::class);

                return new AltoRouterFactory(
                    baseURL: $setting->url(),
                    matchTypes: $matchTypes,
                );
            },
        );

        $this->app->container->singleton(
            RouterContract::class,
            function (ContainerContract $container): RouterContract {
                /** @var RouterFactoryContract */
                $factory = $container->make(RouterFactoryContract::class);

                /** @var RouteBuilder */
                $builder = $container->make(RouteBuilder::class);

                return $factory->create($builder);
            },
        );

        ServiceLocator::factory(
            'router',
            fn () => $this->app->container->make(RouterContract::class),
        );
    }
}
