<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use DateTimeImmutable;
use Illuminate\Support\Collection;

interface ReservationRepositoryInterface
{
    public function lister(): Collection;

    public function listerParSalle(int $salleId): Collection;

    public function trouver(int $id): ?Reservation;

    public function rechercherConflit(int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): ?Reservation;

    public function enregistrer(Reservation $reservation): Reservation;

    public function annuler(Reservation $reservation): Reservation;
}