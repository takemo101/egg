<?php

namespace Takemo101\Egg\Support\Filesystem;

/**
 * extract type enum
 */
enum ExtractType: int
{
    const Dirname = PATHINFO_DIRNAME;
    const Basename = PATHINFO_BASENAME;
    const Extention = PATHINFO_EXTENSION;
    const Filename = PATHINFO_FILENAME;
}
