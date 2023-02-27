<?php

namespace Takemo101\Egg\Support\ResourcePublisher;

/**
 * ファイルシステムでリソースの公開をする
 */
final class PublishResources
{
    /**
     * constructor
     *
     * @param array<string,array<string,string>> $resources
     */
    public function __construct(
        private array $resources = [],
    ) {
        //
    }

    /**
     * 公開リソースを追加する
     *
     * @param string $tag
     * @param array<string,string> $fromTo
     * @return self
     */
    public function add(
        string $tag,
        array $fromTo
    ): self {
        $this->resources[$tag] = [
            ...$fromTo,
            ...($this->resources[$tag] ?? []),
        ];

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
    public function hasTag(string $tag): bool
    {
        return isset($this->resources[$tag]);
    }

    /**
     * タグリストを取得する
     *
     * @return string[]
     */
    public function tags(string $tag): array
    {
        return array_keys($this->resources);
    }
}
