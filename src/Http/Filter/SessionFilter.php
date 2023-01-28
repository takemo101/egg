<?php

namespace Takemo101\Egg\Http\Filter;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Takemo101\Egg\Kernel\Application;

/**
 * セッションを利用するためのフィルタ
 */
class SessionFilter
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
     * セッションを開始する
     *
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Response $response, Closure $next): Response
    {
        $session = new Session(
            $this->app->env()->is('testing')
                ? new MockArraySessionStorage()
                : new NativeSessionStorage(),
        );

        $request->setSession($session);

        $session->start();

        $this->register($session);

        return $next($request, $response);
    }

    /**
     * セッションの依存関係登録
     *
     * @param Session $session
     * @return void
     */
    private function register(Session $session): void
    {
        $this->app->container->instance(Session::class, $session);
        $this->app->container->alias(Session::class, 'session');
    }
}
