<?php

namespace Takemo101\Egg\Support\Filesystem;

/**
 * extract type enum
 */
enum ExtractType: int
{
    public const Dirname = PATHINFO_DIRNAME;
    public const Basename = PATHINFO_BASENAME;
    public const Extention = PATHINFO_EXTENSION;
    public const Filename = PATHINFO_FILENAME;
}
