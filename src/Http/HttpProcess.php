<?php

namespace Takemo101\Egg\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Kernel\Loader\HttpLoader;

/**
 * Httpでリクエストの受け取りから
 * レスポンスを返すまでの処理を簡単に実行する
 */
final class HttpProcess
{
    /**
     * constructor
     *
     * @param HttpDispatcherContract $dispatcher
     * @param ResponseResponderContract $responder
     */
    public function __construct(
        private readonly HttpDispatcherContract $dispatcher,
        private readonly ResponseResponderContract $responder,
    ) {
        //
    }

    /**
     * プロセスを実行する
     *
     * @return void
     */
    public function run(): void
    {
        // ルーティングを処理する
        $response = $this->dispatcher->dispatch(
            request: Request::createFromGlobals(),
            response: new Response(),
        );

        $this->responder->respond($response);
    }

    /**
     * アプリケーションから必要なLoaderを追加して
     * 起動してプロセスを生成する
     *
     * @param Application $app
     * @return self
     */
    public static function fromApplication(
        Application $app,
    ): self {
        $app->addLoader(
            HttpLoader::class,
        )->boot();

        /** @var self */
        $process = $app->container->make(
            self::class,
        );

        return $process;
    }
}
