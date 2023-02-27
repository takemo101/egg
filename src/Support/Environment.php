<?php

namespace Takemo101\Egg\Support;

use Dotenv\Repository\RepositoryInterface;

/**
 * environment
 */
final class Environment
{
    /**
     * constructor
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(
        private readonly RepositoryInterface $repository,
    ) {
        //
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
        /** @var string|null */
        $value = $this->repository->get($key);

        if (is_null($value)) {
            return $default;
        }

        $lower = strtolower($value);

        return match ($lower) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            preg_match('/\A([\'"])(.*)\1\z/', $value, $matches) !== false => $matches[2], /* @phpstan-ignore-line */
            default => $value,
        };
    }
}
