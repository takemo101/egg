<?php

namespace Test\Filesystem;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\Filesystem\LocalSystem;

/**
 * file test
 */
class FilesystemTest extends TestCase
{
    private LocalSystem $filesystem;

    protected function setUp(): void
    {
        $this->filesystem =  new LocalSystem();
    }

    /**
     * @test
     */
    public function ファイルの存在確認__OK()
    {
        $path = $this->path('exists');
        $this->filesystem->write($path, 'content');

        $this->assertTrue($this->filesystem->exists($path));

        $this->filesystem->delete($path);
    }

    /**
     * @test
     */
    public function ファイルの読み込み__OK()
    {
        $path = $this->path('read');
        $content = 'content';
        $this->filesystem->write($path, $content);

        $this->assertEquals($this->filesystem->read($path), $content);

        $this->filesystem->delete($path);
    }

    /**
     * @test
     */
    public function ファイルの書き込み__OK()
    {
        $path = $this->path('write');

        $this->assertTrue($this->filesystem->write($path, 'content'));

        $this->filesystem->delete($path);
    }

    /**
     * @test
     */
    public function ファイル書き込み（手前から）__OK()
    {
        $path = $this->path('prepend');
        $content = 'content';
        $prepend = 'prepend';
        $this->filesystem->write($path, $content);

        $this->filesystem->prepend($path, $prepend);
        $this->filesystem->prepend($path, $prepend);

        $this->assertEquals($this->filesystem->read($path), $prepend . $prepend . $content);

        $this->filesystem->delete($path);
    }

    /**
     * @test
     */
    public function ファイル書き込み（最後から）__OK()
    {
        $path = $this->path('append');
        $content = 'content';
        $append = 'append';
        $this->filesystem->write($path, $content);

        $this->filesystem->append($path, $append);
        $this->filesystem->append($path, $append);

        $this->assertEquals($this->filesystem->read($path), $content . $append . $append);

        $this->filesystem->delete($path);
    }

    /**
     * @test
     */
    public function ファイル削除__OK()
    {
        $path = $this->path('delete');
        $this->filesystem->write($path, 'content');

        $this->assertTrue($this->filesystem->exists($path));

        $this->filesystem->delete($path);

        $this->assertTrue(!$this->filesystem->exists($path));
    }

    public function test__System__chmod__OK()
    {
        $path = $this->path('chmod');
        $this->filesystem->write($path, 'content');
        $this->filesystem->chmod($path, 0o777);

        $this->assertEquals($this->filesystem->permission($path) & 0o777, 0o777);

        $this->filesystem->delete($path);
    }

    /**
     * @test
     */
    public function ファイルのコピー__OK()
    {
        $directory = $this->path('copy-directory');
        $this->filesystem->deleteDirectory($directory, false);
        $this->filesystem->makeDirectory($directory);

        $path = $this->path('copy');
        $this->filesystem->write($path, 'content');
        $copy = $directory . '/copy';
        $this->filesystem->copy($path, $copy);

        $this->assertTrue($this->filesystem->exists($copy));

        $this->filesystem->delete($path);
        $this->filesystem->deleteDirectory($directory, false);
    }

    /**
     * @test
     */
    public function test__System__move__OK()
    {
        $directory = $this->path('move-directory');
        $this->filesystem->deleteDirectory($directory, false);
        $this->filesystem->makeDirectory($directory);

        $path = $this->path('move');
        $this->filesystem->write($path, 'content');
        $move = $directory . '/move';
        $this->filesystem->move($path, $move);

        $this->assertTrue(!$this->filesystem->exists($path));
        $this->assertTrue($this->filesystem->exists($move));

        $this->filesystem->deleteDirectory($directory, false);
    }

    /**
     * @test
     */
    public function シンボリックリンク__OK()
    {
        $directory = $this->path('link-directory');
        $this->filesystem->deleteDirectory($directory, false);
        $this->filesystem->makeDirectory($directory);

        $path = $this->path('link');
        $this->filesystem->delete($path);
        $this->filesystem->symlink($directory, $path);

        $this->assertTrue($this->filesystem->isLink($path));

        $this->filesystem->delete($path);
        $this->filesystem->deleteDirectory($directory, false);
    }

    /**
     * @test
     */
    public function ファイルの一覧__OK()
    {
        $files = [
            'a',
            'b',
            'c',
        ];

        foreach ($files as $file) {
            $path = $this->path($file);
            $this->filesystem->write($path, 'content');
        }

        $glob = $this->filesystem->glob($this->path('*'));

        foreach ($files as $index => $file) {
            $path = $this->path($file);
            $this->filesystem->delete($path);

            $this->assertEquals($glob[$index], $path);
        }
    }

    /**
     * @test
     */
    public function ディレクトリ作成__OK()
    {
        $directory = $this->path('make-directory');
        $this->filesystem->makeDirectory($directory);

        $this->assertTrue($this->filesystem->isDirectory($directory));

        $this->filesystem->deleteDirectory($directory, false);
    }

    /**
     * @test
     */
    public function ディレクトリ移動__OK()
    {
        $directory = $this->path('move-directory');
        $this->filesystem->makeDirectory($directory);

        $files = [
            'a',
            'b',
            'c',
        ];

        foreach ($files as $file) {
            $path = $this->filesystem->helper->join($directory, $file);
            $this->filesystem->write($path, 'content');
        }

        $toDirectory = $this->path('to-directory');

        $this->filesystem->moveDirectory($directory, $toDirectory);

        foreach ($files as $file) {
            $ph = $this->filesystem->helper->join($toDirectory, $file);
            $this->assertTrue($this->filesystem->exists($ph));
        }

        $this->filesystem->deleteDirectory($directory, false);
        $this->filesystem->deleteDirectory($toDirectory, false);
    }

    /**
     * @test
     */
    public function ディレクトリのコピー__OK()
    {
        $directory = $this->path('copy-directory');
        $this->filesystem->makeDirectory($directory);

        $files = [
            'a',
            'b',
            'c',
        ];

        foreach ($files as $file) {
            $path = $this->filesystem->helper->join($directory, $file);
            $this->filesystem->write($path, 'content');
        }

        $toDirectory = $this->path('to-directory');

        $this->filesystem->copyDirectory($directory, $toDirectory);

        foreach ($files as $file) {
            $ph = $this->filesystem->helper->join($toDirectory, $file);
            $this->assertTrue($this->filesystem->exists($ph));
            $ph = $this->filesystem->helper->join($directory, $file);
            $this->assertTrue($this->filesystem->exists($ph));
        }

        $this->filesystem->deleteDirectory($directory, false);
        $this->filesystem->deleteDirectory($toDirectory, false);
    }

    /**
     * テストリソースのパスを取得する
     *
     * @param string $file
     * @return string
     */
    public function path(string $file = ''): string
    {
        $helper = $this->filesystem->helper;

        $path = $helper->join(__DIR__, 'resource');
        return $file ? $helper->join($path, $file) : $path;
    }
}
