<?php

declare(strict_types=1);

namespace App\Console;

interface CommandInterface
{
    public function nom(): string;

    public function executer(array $arguments): void;
}