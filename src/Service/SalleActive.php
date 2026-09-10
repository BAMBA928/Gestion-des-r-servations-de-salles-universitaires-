<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Exception\SalleIndisponibleException;

class SalleActive implements ReservationInterface
{
    public function verifier(CreerReservationDTO $dto, Salle $salle, ReservationRepositoryInterface $reservationRepository): void
    {
        if (!$salle->active) {
            throw new SalleIndisponibleException("Cette salle ne peut pas être réservée.");
        }
    }
}