<?php

namespace Takemo101\Egg\Http\Testing;

use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Http\HttpDispatcherContract;

/**
 * テストのためのRequestを実行する
 */
final class RequestExecuter
{
    /**
     * constructor
     *
     * @param HttpDispatcherContract $dispatcher
     * @param RequestCreator $creator
     */
    public function __construct(
        private readonly HttpDispatcherContract $dispatcher,
        private readonly RequestCreator $creator,
    ) {
        //
    }

    /**
     * 空のレスポンスを作成する
     *
     * @return Response
     */
    private function createEmptyResponse(): Response
    {
        return new Response();
    }

    /**
     * Get リクエスト
     *
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    public function get(
        string $uri,
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createRequest(
                method: 'GET',
                uri: $uri,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Get jsonリクエスト
     *
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    public function getJson(
        string $uri,
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createJsonRequest(
                method: 'GET',
                uri: $uri,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Post リクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function post(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createRequest(
                method: 'POST',
                uri: $uri,
                parameters: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Post jsonリクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function postJson(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createJsonRequest(
                method: 'POST',
                uri: $uri,
                data: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Put リクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function put(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createRequest(
                method: 'PUT',
                uri: $uri,
                parameters: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Put jsonリクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function putJson(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createJsonRequest(
                method: 'PUT',
                uri: $uri,
                data: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Patch リクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function patch(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createRequest(
                method: 'PATCH',
                uri: $uri,
                parameters: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Patch jsonリクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function patchJson(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createJsonRequest(
                method: 'PATCH',
                uri: $uri,
                data: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Delete リクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function delete(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createRequest(
                method: 'DELETE',
                uri: $uri,
                parameters: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Delete jsonリクエスト
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function deleteJson(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createJsonRequest(
                method: 'DELETE',
                uri: $uri,
                data: $data,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Options リクエスト
     *
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    public function options(
        string $uri,
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createRequest(
                method: 'OPTIONS',
                uri: $uri,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Options jsonリクエスト
     *
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    public function optionsJson(
        string $uri,
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createJsonRequest(
                method: 'OPTIONS',
                uri: $uri,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }

    /**
     * Head リクエスト
     *
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    public function head(
        string $uri,
        array $headers = [],
    ): Response {
        return $this->dispatcher->dispatch(
            $this->creator->createRequest(
                method: 'HEAD',
                uri: $uri,
                server: $this->creator->convertHeadersToServer($headers),
            ),
            $this->createEmptyResponse(),
        );
    }
}
