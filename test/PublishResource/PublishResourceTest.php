<?php

namespace Test\Kernel\PublishResource;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\Filesystem\LocalSystem;
use Takemo101\Egg\Support\ResourcePublisher\PublishResources;
use Takemo101\Egg\Support\ResourcePublisher\ResourcePublisher;

/**
 * publish resource test
 */
class PublishResourceTest extends TestCase
{
    /**
     * @test
     */
    public function リソースを公開する__OK()
    {
        $resources = new PublishResources();

        $tag = 'test';

        $resourceDirectory = dirname(__DIR__, 1);

        $to = $resourceDirectory . '/resource/to/test.php';

        $resources->set(
            $tag,
            [
                $resourceDirectory . '/resource/from/test.php' => $to,
            ],
        );

        $fs = new LocalSystem();

        $publisher = new ResourcePublisher($fs);

        $this->assertTrue($resources->has($tag));

        foreach ($resources->get($tag) as $from => $to) {
            $publisher->publish($from, $to);
        }

        $this->assertTrue($fs->exists($to));

        $fs->delete($to);
    }
}
