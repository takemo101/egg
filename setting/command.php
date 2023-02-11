<?php

use Takemo101\Egg\Console\Command\VersionCommand;
use Takemo101\Egg\Console\Commands;

return function (Commands $commands) {
    $commands->add(
        VersionCommand::class,
    );
};
