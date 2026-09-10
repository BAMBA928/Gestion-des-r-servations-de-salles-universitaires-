<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Exception\SalleIndisponibleException;

class DateFuture implements ReservationInterface
{
    public function verifier(CreerReservationDTO $dto, Salle $salle, ReservationRepositoryInterface $reservationRepository): void
    {
          if ($dto->dateDebut <= new \DateTimeImmutable()) {
            throw new SalleIndisponibleException("La réservation doit commencer dans le futur.");
        }
    }
}