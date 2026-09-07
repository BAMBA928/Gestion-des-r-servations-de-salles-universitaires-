<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__)  . '/config/database.php';

echo "Connexion OK\n";
// $app = new App\Application();
// $app->run();
use App\Model\Salle;

// $salle = new Salle();
// $salle->nom = 'Salle Test';
// $salle->batiment = 'Bloc A';
// $salle->capacite = 20;
// $salle->type = 'cours';
// $salle->active = true;
// $salle->save();

// echo "Créée le : " . $salle->created_at . "\n";
// echo "Modifiée le : " . $salle->updated_at . "\n";

// // Maintenant on modifie
// sleep(2);
// $salle->capacite = 25;
// $salle->save();

// echo "Après modification :\n";
// echo "Créée le : " . $salle->created_at . "\n";   // ne change pas
// echo "Modifiée le : " . $salle->updated_at . "\n"; // change !