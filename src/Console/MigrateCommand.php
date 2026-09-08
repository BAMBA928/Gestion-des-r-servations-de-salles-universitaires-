<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Database\Capsule\Manager as Capsule;


final class MigrateCommand implements CommandInterface
{
    public function __construct(
        private Capsule $capsule,
    ) {
    }

    public function nom(): string
    {
        return 'migrate';
    }

    public function executer(array $arguments): void
    {
        $fichiers = glob(dirname(__DIR__, 2) . '/database/migrations/*.php');
        sort($fichiers);

        foreach ($fichiers as $fichier) {
            $migration = require $fichier;
            echo "Exécution de : " . basename($fichier) . "\n";
            $migration($this->capsule);
        }

        echo "Migrations terminées.\n";
    }
}