<?php

namespace Takemo101\Egg\Http\Filter;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * セッションを利用するためのフィルタ
 */
class SessionFilter
{
    /**
     * constructor
     *
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly ContainerContract $container,
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
        $session = new Session();

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
        $this->container->instance(Session::class, $session);
        $this->container->alias(Session::class, 'session');
    }
}
