<?php

namespace Takemo101\Egg\Http\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResponseResolverのコレクション
 */
final class ResponseResolvers implements ResponseResolverContract
{
    /**
     * @var ResponseResolverContract[]
     */
    private array $resolvers;

    /**
     * constructor
     *
     * @param ResponseResolverContract ...$resolvers
     */
    public function __construct(
        ResponseResolverContract ...$resolvers
    ) {
        $this->resolvers = $resolvers;
    }

    /**
     * 受け取った結果をレスポンスに変換する
     * 必ずResponse型でなくてもよくて
     * 最終的にResponse型になってなければ
     * 元々のレスポンスを返す
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $result
     * @return mixed
     */
    public function resolve(
        Request $request,
        Response $response,
        mixed $result,
    ): mixed {
        foreach ($this->resolvers as $resolver) {
            $result = $resolver->resolve($request, $response, $result);
        }

        return $result;
    }

    /**
     * ResponseResolverを追加する
     *
     * @param ResponseResolverContract $resolver
     * @return self
     */
    public function add(
        ResponseResolverContract $resolver
    ): self {
        $this->resolvers[] = $resolver;

        return $this;
    }
}
