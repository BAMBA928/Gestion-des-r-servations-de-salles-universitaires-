<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Service\CreerReservationService;
use DateTimeImmutable;

final class CreerReservationServiceTest extends TestCase
{
    private InMemorySalleRepository $salles;
    private InMemoryReservationRepository $reservations;
    private CreerReservationService $service;

    protected function setUp(): void
    {
        parent::setUp();  // <-- démarre Eloquent via la classe de base

        $this->salles = new InMemorySalleRepository();
        $this->reservations = new InMemoryReservationRepository();
        $this->service = new CreerReservationService($this->salles, $this->reservations);
    }

    private function creerSalleActive(int $id = 1): Salle
    {
        $salle = new Salle();
        $salle->id = $id;
        $salle->nom = 'Salle Test';
        $salle->batiment = 'Bloc A';
        $salle->capacite = 30;
        $salle->type = 'cours';
        $salle->active = true;
        $this->salles->ajouter($salle);

        return $salle;
    }

    public function test_reservation_valide_est_acceptee(): void
    {
        $this->creerSalleActive();

        $dto = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@universite.sn',
            'motif' => 'Cours d\'architecture logicielle',
            'date_debut' => (new DateTimeImmutable('+1 day 10:00')),
            'date_fin' => (new DateTimeImmutable('+1 day 12:00')),
        ]);

        $reservation = $this->service->creer($dto);

        $this->assertInstanceOf(Reservation::class, $reservation);
        $this->assertSame('confirmée', $reservation->statut);
    }

    public function test_salle_inexistante_est_rejetee(): void
    {
        $dto = CreerReservationDTO::depuisTableau([
            'salle_id' => 999,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@universite.sn',
            'motif' => 'Cours',
            'date_debut' => new DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new DateTimeImmutable('+1 day 12:00'),
        ]);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dto);
    }

    public function test_salle_inactive_est_rejetee(): void
    {
        $salle = $this->creerSalleActive();
        $salle->active = false;
        $this->salles->ajouter($salle);

        $dto = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@universite.sn',
            'motif' => 'Cours',
            'date_debut' => new DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new DateTimeImmutable('+1 day 12:00'),
        ]);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dto);
    }

    public function test_date_fin_anterieure_au_debut_est_rejetee(): void
    {
        $this->creerSalleActive();

        $dto = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@universite.sn',
            'motif' => 'Cours',
            'date_debut' => new DateTimeImmutable('+1 day 12:00'),
            'date_fin' => new DateTimeImmutable('+1 day 10:00'),
        ]);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dto);
    }

    public function test_duree_superieure_a_quatre_heures_est_rejetee(): void
    {
        $this->creerSalleActive();

        $dto = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@universite.sn',
            'motif' => 'Cours',
            'date_debut' => new DateTimeImmutable('+1 day 08:00'),
            'date_fin' => new DateTimeImmutable('+1 day 14:00'), // 6h
        ]);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dto);
    }

    public function test_date_passee_est_rejetee(): void
    {
        $this->creerSalleActive();

        $dto = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@universite.sn',
            'motif' => 'Cours',
            'date_debut' => new DateTimeImmutable('-1 day 10:00'),
            'date_fin' => new DateTimeImmutable('-1 day 12:00'),
        ]);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dto);
    }

    public function test_conflit_avec_reservation_existante_est_rejete(): void
    {
        $this->creerSalleActive();

        // réservation existante 10h -> 12h
        $premiereDto = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Premier',
            'email' => 'premier@universite.sn',
            'motif' => 'Premier cours',
            'date_debut' => new DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new DateTimeImmutable('+1 day 12:00'),
        ]);
        $this->service->creer($premiereDto);

        // nouvelle demande 11h -> 13h -> chevauchement
        $dtoConflit = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Second',
            'email' => 'second@universite.sn',
            'motif' => 'Second cours',
            'date_debut' => new DateTimeImmutable('+1 day 11:00'),
            'date_fin' => new DateTimeImmutable('+1 day 13:00'),
        ]);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dtoConflit);
    }

    public function test_reservation_voisine_sans_chevauchement_est_acceptee(): void
    {
        $this->creerSalleActive();

        // réservation existante 10h -> 12h
        $premiereDto = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Premier',
            'email' => 'premier@universite.sn',
            'motif' => 'Premier cours',
            'date_debut' => new DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new DateTimeImmutable('+1 day 12:00'),
        ]);
        $this->service->creer($premiereDto);

        // nouvelle demande 12h -> 14h -> pas de chevauchement (voisine)
        $dtoVoisin = CreerReservationDTO::depuisTableau([
            'salle_id' => 1,
            'responsable' => 'Second',
            'email' => 'second@universite.sn',
            'motif' => 'Second cours',
            'date_debut' => new DateTimeImmutable('+1 day 12:00'),
            'date_fin' => new DateTimeImmutable('+1 day 14:00'),
        ]);

        $reservation = $this->service->creer($dtoVoisin);

        $this->assertInstanceOf(Reservation::class, $reservation);
    }
}
