<?php

use App\Console\MigrateCommand;
use App\Console\SeedCommand;

use function DI\autowire;

return [
    MigrateCommand::class => autowire(),
    SeedCommand::class => autowire(),
];