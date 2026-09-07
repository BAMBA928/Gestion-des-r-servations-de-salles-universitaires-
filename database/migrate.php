<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
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

$fichiers = glob(__DIR__ . '/migrations/*.php');
sort($fichiers); // garantit l'ordre 001, 002, ...

foreach ($fichiers as $fichier) {
    $migration = require $fichier;

    $nomTable = basename($fichier);
    echo "Exécution de : {$nomTable}\n";

    $migration($capsule);
}

echo "Migrations terminées.\n";