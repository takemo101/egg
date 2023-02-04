<?php

use Takemo101\Egg\Http\Filter\MethodOverrideFilter;
use Takemo101\Egg\Http\Filter\SessionFilter;

/**
 * @return array<object|mixed[]|class-string>
 */
return [
    MethodOverrideFilter::class,
    SessionFilter::class,
];
