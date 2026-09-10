<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Exception\SalleIndisponibleException;


class DateOrdre implements ReservationInterface
{
    public function verifier(CreerReservationDTO $dto, Salle $salle, ReservationRepositoryInterface $reservationRepository): void
    {
        if ($dto->dateDebut >= $dto->dateFin) {
            throw new SalleIndisponibleException("La date de début doit précéder la date de fin.");
        }
    }
}