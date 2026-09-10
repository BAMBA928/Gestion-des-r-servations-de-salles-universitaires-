<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

interface ReservationInterface
{
    public function verifier(
        CreerReservationDTO $dto,
        Salle $salle,
        ReservationRepositoryInterface $reservationRepository
    ): void;
}