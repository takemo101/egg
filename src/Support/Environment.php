<?php

namespace Takemo101\Egg\Support;

use Takemo101\Egg\Support\Arr\ArrAccess;

/**
 * environment
 */
final class Environment
{
    /**
     * @var ArrAccess
     */
    private readonly ArrAccess $env;

    /**
     * constructor
     *
     * @param array<string,mixed> $env
     */
    public function __construct(
        array $env,
    ) {
        $this->env = new ArrAccess($env);
    }

    /**
     * get env
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        /** @var string */
        $value = $this->env->get($key, $default);

        $lower = strtolower($value);

        return match ($lower) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            preg_match('/\A([\'"])(.*)\1\z/', $value, $matches) => $matches[2],
            default => $value,
        };
    }
}
