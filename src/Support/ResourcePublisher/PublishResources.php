<?php

namespace Takemo101\Egg\Support\ResourcePublisher;

/**
 * ファイルシステムでリソースの公開をする
 */
final class PublishResources
{
    /**
     * @var array<string,array<string,string>>
     */
    private array $resources = [];

    /**
     * constructor
     *
     * @param array<string,array<string,string>> $resources
     */
    public function __construct(
        array $resources = [],
    ) {
        foreach ($resources as $tag => $fromTo) {
            $this->set($tag, $fromTo);
        }
    }

    /**
     * 公開リソースを設定する
     *
     * @param string $tag
     * @param array<string,string> $fromTo
     * @return self
     */
    public function set(
        string $tag,
        array $fromTo
    ): self {
        $this->resources[$tag] = $fromTo;

        return $this;
    }

    /**
     * タグからリソース設定を取得する
     *
     * @return array<string,string>
     */
    public function get(string $tag): array
    {
        return $this->resources[$tag] ?? [];
    }

    /**
     * タグが存在するか
     *
     * @param string $tag
     * @return boolean
     */
    public function has(string $tag): bool
    {
        return isset($this->resources[$tag]);
    }

    /**
     * タグリストを取得する
     *
     * @return string[]
     */
    public function tags(): array
    {
        return array_keys($this->resources);
    }
}
