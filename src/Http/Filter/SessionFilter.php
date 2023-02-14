<?php

namespace Takemo101\Egg\Http\Filter;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
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
     * セッションストレージを生成する
     * 派生クラスでオーバーライドして利用する
     *
     * @return SessionStorageInterface
     */
    protected function createSessionStorage(): SessionStorageInterface
    {
        return new NativeSessionStorage(
            config('session.options', []),
        );
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
                : $this->createSessionStorage(),
        );

        $request->setSession($session);

        $session->start();

        $this->register($session);

        /** @var Response */
        $result = $next($request, $response);

        $session->save();

        return $result;
    }

    /**
     * セッションの依存関係登録
     *
     * @param Session $session
     * @return void
     */
    private function register(Session $session): void
    {
        $this->app->container
            ->alias(Session::class, 'session')
            ->alias(Session::class, FlashBagAwareSessionInterface::class)
            ->alias(Session::class, SessionInterface::class)
            ->instance(Session::class, $session);
    }
}
