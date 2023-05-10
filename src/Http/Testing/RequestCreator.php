<?php

namespace Takemo101\Egg\Http\Testing;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * テストのためのRequestを生成する
 */
final class RequestCreator
{
    /**
     * jsonリクエストを作成する
     *
     * @param string $method
     * @param string $uri
     * @param array<string,mixed> $data
     * @param array<string,mixed> $server
     * @return Request
     */
    public function createJsonRequest(
        string $method,
        string $uri,
        array $data = [],
        array $cookies = [],
        array $server = [],
    ): Request {
        $files = $this->extractFiles($data);

        $content = json_encode($data);

        $server = [
            ...[
                'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
            ],
            ...$server,
        ];

        return $this->createRequest(
            $method,
            $uri,
            [],
            $cookies,
            $files,
            $server,
            $content
        );
    }

    /**
     * リクエストを生成する
     *
     * @param string $method
     * @param string $uri
     * @param array<string,mixed> $parameters
     * @param array<string,mixed> $cookies
     * @param array<string,mixed> $files
     * @param array<string,mixed> $server
     * @param string|null $content
     * @return Request
     */
    public function createRequest(
        string $method,
        string $uri,
        array $parameters = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        ?string $content = null,
    ): Request {
        $files = [
            ...$files,
            ...$this->extractFiles($parameters),
        ];

        return Request::create(
            $uri,
            $method,
            $parameters,
            $cookies,
            $files,
            $server,
            $content
        );
    }

    /**
     * ヘッダーをサーバー変数に変換する
     *
     * @param array<string,mixed> $headers
     * @return array<string,mixed>
     */
    public function convertHeadersToServer(array $headers): array
    {
        $result = [];

        foreach ($headers as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtr(strtoupper($key), '-', '_');;

                $result[$name] = $value;
            }
        }

        return $result;
    }

    /**
     * 配列データからファイルを抽出する
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    private function extractFiles(array &$data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                $result[$key] = $value;

                unset($data[$key]);
            }

            if (is_array($value)) {
                $result[$key] = $this->extractFiles($value);

                $result[$key] = $value;
            }
        }

        return $result;
    }
}
