<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;
use Illuminate\Database\ConnectionResolver;

final class MigrateCommand implements CommandInterface
{
    public function nom(): string
    {
        return 'migrate';
    }

    public function executer(array $arguments): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'    => $_ENV['DB_DRIVER'],
            'host'      => $_ENV['DB_HOST'],
            'port'      => $_ENV['DB_PORT'],
            'database'  => $_ENV['DB_DATABASE'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $fichiers = glob(dirname(__DIR__, 2) . '/database/migrations/*.php');
        sort($fichiers);

        foreach ($fichiers as $fichier) {
            $migration = require $fichier;
            echo "Exécution de : " . basename($fichier) . "\n";
            $migration($capsule);
        }

        echo "Migrations terminées.\n";
    }
}