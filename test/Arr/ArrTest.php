<?php

namespace Test\Arr;

use PHPUnit\Framework\TestCase;
use Takemo101\Egg\Support\Arr\Arr;

/**
 * arr test
 */
class ArrTest extends TestCase
{
    /**
     * @test
     */
    public function ドット区切りで配列の値を取得__OK()
    {
        $data = 'data';
        $array = [
            'a' => [
                'b' => [
                    'c' => $data,
                ],
            ],
        ];

        $get = Arr::get($array, 'a.b.c');

        $this->assertEquals($get, $data);
    }

    /**
     * @test
     */
    public function ドット区切りで配列に値をセット__OK()
    {
        $data = 'data';
        $array = [
            'a' => [
                'b' => [
                    'c' => 'c',
                ],
            ],
        ];

        Arr::set($array, 'a.b.c', $data);

        $this->assertEquals(Arr::get($array, 'a.b.c'), $data);
    }

    /**
     * @test
     */
    public function ドット区切りで配列の値を削除__OK()
    {
        $key = 'a.b.c';
        $array = [
            'a' => [
                'b' => [
                    'c' => 'c',
                ],
            ],
        ];

        Arr::forget($array, $key);

        $this->assertTrue(!Arr::has($array, $key));
    }

    /**
     * @test
     */
    public function ドット記法の配列に変換する__OK()
    {
        $key = 'a.b.c';
        $array = [
            'a' => [
                'b' => [
                    'c' => 'c',
                ],
            ],
            'b' => 'b',
        ];

        $dot = Arr::dot($array);

        $this->assertEquals(count($dot), 2);
        $this->assertTrue(array_key_exists($key, $dot));
    }

    /**
     * @test
     */
    public function ドット記法の配列をネスとした配列に変換__OK()
    {
        $key = 'a.b.c';

        $undot = Arr::undot([
            $key => 'c',
        ]);

        $this->assertTrue(Arr::has($undot, $key));
    }
}
