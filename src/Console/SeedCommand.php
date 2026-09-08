<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

final class SeedCommand implements CommandInterface
{
    public function nom(): string
    {
        return 'seed';
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

        $salles = [
            ['nom' => 'Amphithéâtre A', 'batiment' => 'Bloc A', 'capacite' => 250, 'type' => 'amphitheatre', 'active' => true],
            ['nom' => 'Salle B12', 'batiment' => 'Bloc B', 'capacite' => 40, 'type' => 'cours', 'active' => true],
            ['nom' => 'Laboratoire Chimie', 'batiment' => 'Bloc C', 'capacite' => 24, 'type' => 'laboratoire', 'active' => true],
            ['nom' => 'Salle Informatique 1', 'batiment' => 'Bloc B', 'capacite' => 30, 'type' => 'informatique', 'active' => true],
            ['nom' => 'Salle de réunion', 'batiment' => 'Bloc A', 'capacite' => 12, 'type' => 'reunion', 'active' => true],
        ];

        foreach ($salles as $donnees) {
            Salle::firstOrCreate(['nom' => $donnees['nom']], $donnees);
        }

        echo "Données initiales insérées avec succès.\n";
    }
}