<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

final class SalleReservationIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        $capsule = new Capsule();
        $capsule->addConnection(['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $capsule->schema()->create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('batiment');
            $table->unsignedInteger('capacite');
            $table->string('type');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $capsule->schema()->create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salle_id');
            $table->string('responsable');
            $table->string('email');
            $table->string('motif');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('statut')->default('confirmée');
            $table->timestamps();
        });
    }

    public function test_creation_salle_avec_eloquent(): void
    {
        $repo = new EloquentSalleRepository();
        $salle = new Salle();
        $salle->nom = 'Amphithéâtre A';
        $salle->batiment = 'Bloc A';
        $salle->capacite = 250;
        $salle->type = 'amphitheatre';
        $salle->active = true;

        $repo->enregistrer($salle);

        $this->assertNotNull($salle->id);
        $this->assertSame('Amphithéâtre A', $repo->trouver($salle->id)->nom);
    }

    public function test_relation_salle_reservations(): void
    {
        $salleRepo = new EloquentSalleRepository();
        $salle = new Salle();
        $salle->nom = 'Salle B12';
        $salle->batiment = 'Bloc B';
        $salle->capacite = 40;
        $salle->type = 'cours';
        $salle->active = true;
        $salleRepo->enregistrer($salle);

        $reservationRepo = new EloquentReservationRepository();
        $reservation = new Reservation();
        $reservation->salle_id = $salle->id;
        $reservation->responsable = 'Awa Ndiaye';
        $reservation->email = 'awa@universite.sn';
        $reservation->motif = 'Cours';
        $reservation->date_debut = '2026-09-10 10:00';
        $reservation->date_fin = '2026-09-10 12:00';
        $reservation->statut = 'confirmée';
        $reservationRepo->enregistrer($reservation);

        $this->assertCount(1, $salle->reservations);
        $this->assertSame($salle->id, $reservation->salle->id);
    }

    public function test_recherche_de_chevauchement(): void
    {
        $salleRepo = new EloquentSalleRepository();
        $salle = new Salle();
        $salle->nom = 'Salle Test';
        $salle->batiment = 'Bloc A';
        $salle->capacite = 30;
        $salle->type = 'cours';
        $salle->active = true;
        $salleRepo->enregistrer($salle);

        $reservationRepo = new EloquentReservationRepository();
        $reservation = new Reservation();
        $reservation->salle_id = $salle->id;
        $reservation->responsable = 'Awa Ndiaye';
        $reservation->email = 'awa@universite.sn';
        $reservation->motif = 'Cours';
        $reservation->date_debut = '2026-09-10 10:00';
        $reservation->date_fin = '2026-09-10 12:00';
        $reservation->statut = 'confirmée';
        $reservationRepo->enregistrer($reservation);

        $conflit = $reservationRepo->rechercherConflit(
            $salle->id,
            new \DateTimeImmutable('2026-09-10 11:00'),
            new \DateTimeImmutable('2026-09-10 13:00')
        );

        $this->assertNotNull($conflit);
    }

    public function test_annulation_reservation(): void
    {
        $salleRepo = new EloquentSalleRepository();
        $salle = new Salle();
        $salle->nom = 'Salle Test';
        $salle->batiment = 'Bloc A';
        $salle->capacite = 30;
        $salle->type = 'cours';
        $salle->active = true;
        $salleRepo->enregistrer($salle);

        $reservationRepo = new EloquentReservationRepository();
        $reservation = new Reservation();
        $reservation->salle_id = $salle->id;
        $reservation->responsable = 'Awa Ndiaye';
        $reservation->email = 'awa@universite.sn';
        $reservation->motif = 'Cours';
        $reservation->date_debut = '2026-09-10 10:00';
        $reservation->date_fin = '2026-09-10 12:00';
        $reservation->statut = 'confirmée';
        $reservationRepo->enregistrer($reservation);

        $reservationRepo->annuler($reservation);

        $this->assertSame('annulée', $reservationRepo->trouver($reservation->id)->statut);
    }
}