<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;

final class SeedCommand implements CommandInterface
{
    public function __construct(
        private Capsule $capsule,
    ) {
    }

    public function nom(): string
    {
        return 'seed';
    }

    public function executer(array $arguments): void
    {
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