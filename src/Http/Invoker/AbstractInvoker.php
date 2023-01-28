<?php

namespace Takemo101\Egg\Http\Invoker;

use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Takemo101\Egg\Support\Shared\CallableCreator;

/**
 * Invokerの抽象クラス
 */
abstract class AbstractInvoker
{
    /**
     * @var CallableCreator
     */
    protected readonly CallableCreator $creator;

    /**
     * constructor
     *
     * @param ContainerContract $container
     */
    public function __construct(
        protected readonly ContainerContract $container,
    ) {
        $this->creator = new CallableCreator($container);
    }


    /**
     * コールバックからの出力結果を比較して
     * レスポンスでない場合は、通常のレスポンスを返す
     *
     * @param mixed $result
     * @param Response $response
     * @return Response
     */
    protected function orResponse(mixed $result, Response $response): Response
    {
        return $result && ($result instanceof Response)
            ? $result
            : $response;
    }
}
