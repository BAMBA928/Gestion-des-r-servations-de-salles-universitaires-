<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__)  . '/config/database.php';

// echo "Connexion OK\n";
// // $app = new App\Application();
// // $app->run();
// use App\Model\Salle;

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

use App\Validation\ReservationValidator;
use App\DTO\CreerReservationDTO;
use App\Service\CreerReservationService;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
// public ReservationRepositoryInterface $reservation;
$validator = new ReservationValidator();
$resultat = $validator->validate([
    'salle_id' => 1,
    'responsable' => 'Awa Ndiaye',
    'email' => 'email21@gmail.com',
    'motif' => 'TP',
    'date_debut' => '2026-09-10',
    'date_fin' => '2026-09-10',
]);

var_dump($resultat->isValid()); // false
print_r($resultat->errors());  // erreurs sur 'email' et 'motif'
if($resultat->isValid()){
$dto=CreerReservationDTO::depuisTableau($resultat->data());


// $service=new CreerReservationService(ReservationRepositoryInterface::$reservation,SalleRepositoryInterface::$salle);
// $service->creer($dto);
// var_dump($service);
}