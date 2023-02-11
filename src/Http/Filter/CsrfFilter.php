<?php

namespace Takemo101\Egg\Http\Filter;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Exception;
use Takemo101\Egg\Http\Exception\CsrfTokenMismatchHttpException;

/**
 * Csrf対策のフィルタ
 * Sessionが必要なので
 * SessionFilterの後に実行する必要がある
 */
class CsrfFilter
{
    public const TokenLength = 32;

    public const TokenKey = '_csrf';

    public const TokenHeader = 'X-CSRF-TOKEN';

    protected ?string $token = null;

    /**
     * constructor
     *
     * @param Session $session
     */
    public function __construct(
        private readonly Session $session,
    ) {
        //
    }

    /**
     * POST系のリクエストの場合は
     * トークンのバリデーションをする
     *
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return Response
     * @throws CsrfTokenMismatchHttpException
     */
    public function handle(Request $request, Response $response, Closure $next): Response
    {
        /** @var string|null */
        $token = $request->get($this->key()) ?: $request->server->get(self::TokenHeader);

        if (
            in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])
            && (!$token || !$this->validateToken($token))
        ) {
            throw new CsrfTokenMismatchHttpException();
        }

        return $next($request, $response);;
    }

    /**
     * トークンの値を生成する
     *
     * @return string
     * @throws Exception
     */
    protected function createToken(): string
    {
        return bin2hex(random_bytes(self::TokenLength));
    }

    /**
     * トークンを生成する
     *
     * @return string
     * @throws Exception
     */
    public function generateToken(): string
    {
        $token = $this->createToken();

        $this->session->set($this->key(), $token);

        return $this->token = $this->encodeToken($token);
    }

    /**
     * トークンのバリデーションを行う
     *
     * @param  string $token
     * @return bool
     */
    public function validateToken(string $token): bool
    {
        $key = $this->key();

        if (!$this->session->has($key)) {
            return false;
        }

        /** @var string */
        $sessionToken = $this->session->get($key);

        return hash_equals($sessionToken, $this->decodeToken($token));
    }

    /**
     * 現在のエンコードされたトークンを取得する
     * トークンが存在しない場合は生成する
     *
     * @return string|null
     */
    public function token(): ?string
    {
        if ($this->token) {
            return $this->token;
        }

        if ($token = $this->session->get($this->key())) {
            return $this->token = $this->encodeToken($token);
        }

        return $this->generateToken();
    }

    /**
     * トークンのキーを取得する
     *
     * @return string
     */
    public function key(): string
    {
        return self::TokenKey;
    }

    /**
     * トークンをクリアする
     *
     * @return void
     */
    public function clearToken(): void
    {
        $this->session->remove($this->key());
    }

    /**
     * トークンの暗号化
     *
     * @param string $token エンコードするトークン
     *
     * @return string エンコード処理されたトークン
     * @throws Exception
     */
    private function encodeToken(string $token): string
    {
        $key = random_bytes(strlen($token));

        return base64_encode($key . ($key ^ $token));
    }

    /**
     * トークンの複合
     *
     * @param string $encodedToken デコードするトークン
     * @return string デコードされたトークン
     */
    private function decodeToken(string $encodedToken): string
    {
        $decoded = base64_decode($encodedToken, true);

        if ($decoded === false) {
            return '';
        }
        $tokenLength = strlen($decoded) / 2;

        if (!is_int($tokenLength)) {
            return '';
        }

        $key = substr($decoded, 0, $tokenLength);

        $decodedMaskedToken = substr(
            $decoded,
            $tokenLength,
            $tokenLength,
        );

        return $key ^ $decodedMaskedToken;
    }
}
