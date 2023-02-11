<?php

use Takemo101\Egg\Http\Filter\CsrfFilter;
use Takemo101\Egg\Http\Filter\MethodOverrideFilter;
use Takemo101\Egg\Http\Filter\SessionFilter;
use Takemo101\Egg\Http\RootFilters;

return function (RootFilters $filters) {
    $filters->add(
        MethodOverrideFilter::class,
        SessionFilter::class,
        CsrfFilter::class,
    );
};
