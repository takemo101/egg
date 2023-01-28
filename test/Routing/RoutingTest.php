<?php

namespace Test\Routing;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Takemo101\Egg\Routing\AltoRouter\AltoRouterFactory;
use Takemo101\Egg\Routing\RouteBuilder;

/**
 * routing test
 */
class RoutingTest extends TestCase
{
    /**
     * @test
     */
    public function メソッド指定でのルート__OK()
    {
        $builder = new RouteBuilder();

        $route = '/test';

        $builder->get($route, fn () => 'test');
        $builder->post($route, fn () => 'test');
        $builder->put($route, fn () => 'test');
        $builder->patch($route, fn () => 'test');
        $builder->delete($route, fn () => 'test');
        $builder->options($route, fn () => 'test');

        $anyRoute = '/any';

        $builder->any($anyRoute, fn () => 'test');

        $factory = new AltoRouterFactory();
        $router = $factory->create($builder);

        $methods = [
            'get',
            'post',
            'put',
            'patch',
            'delete',
            'options',
        ];

        foreach ($methods as $method) {
            $result = $router->match(
                AltoRouterFactory::DefaultBaseURL . $route,
                $method,
            );

            $this->assertNotNull(
                $result,
                "method: {$method} route: {$route} ルートが存在する"
            );
        }

        foreach ($methods as $method) {
            $result = $router->match(
                AltoRouterFactory::DefaultBaseURL . $anyRoute,
                $method,
            );

            $this->assertNotNull(
                $result,
                "method: {$method} route: {$route} ルートが存在する"
            );
        }
    }

    /**
     * @test
     */
    public function メソッド指定でのルート__NG()
    {
        $builder = new RouteBuilder();

        $route = '/test';

        $factory = new AltoRouterFactory();
        $router = $factory->create($builder);

        $methods = [
            'get',
            'post',
            'put',
            'patch',
            'delete',
            'options',
        ];

        foreach ($methods as $method) {
            $result = $router->match(
                AltoRouterFactory::DefaultBaseURL . $route,
                $method,
            );

            $this->assertNull(
                $result,
                "method: {$method} route: {$route} ルートが存在しない"
            );
        }
    }

    /**
     * @test
     */
    public function どのルートにも一致しないルート__OK()
    {
        $builder = new RouteBuilder();

        $builder->nothing(fn () => 'test');

        $factory = new AltoRouterFactory();
        $router = $factory->create($builder);

        foreach (range(0, 10) as $i) {
            $unique = '/' . uniqid();

            $result = $router->match(
                AltoRouterFactory::DefaultBaseURL . $unique,
                'get',
            );

            $this->assertNotNull(
                $result,
                "route: {$unique} ルートが存在する"
            );
        }
    }

    /**
     * @test
     */
    public function 名付けルート__OK()
    {
        $builder = new RouteBuilder();

        $idRoute = '/test/[i:id]';
        $idRouteName = 'test.id';

        $builder->get($idRoute, fn () => 'test')->name($idRouteName);

        $slugRoute = '/test/[s:id]';
        $slugRouteName = 'test.slug';

        $builder->get($slugRoute, fn () => 'test')->name($slugRouteName);

        $factory = new AltoRouterFactory();
        $router = $factory->create($builder);

        $id = 1;
        $url = $router->route($idRouteName, ['id' => $id]);

        $this->assertEquals(
            AltoRouterFactory::DefaultBaseURL . '/test/' . $id,
            $url,
            "name: {$idRouteName} 名前付きルートが存在する"
        );

        $id = 'slug-slug';
        $url = $router->route($slugRouteName, ['id' => $id]);

        $this->assertEquals(
            AltoRouterFactory::DefaultBaseURL . '/test/' . $id,
            $url,
            "name: {$slugRouteName} 名前付きルートが存在する"
        );
    }

    /**
     * @test
     */
    public function 名付けルート__NG()
    {
        $this->expectException(RuntimeException::class);

        $builder = new RouteBuilder();

        $factory = new AltoRouterFactory();
        $router = $factory->create($builder);

        $router->route('test');
    }
}
