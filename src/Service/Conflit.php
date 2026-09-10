<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Exception\SalleIndisponibleException;

class Conflit implements ReservationInterface
{
    public function verifier(CreerReservationDTO $dto, Salle $salle, ReservationRepositoryInterface $reservationRepository): void
    {
       
        $conflit = $reservationRepository->rechercherConflit($dto->salleId, $dto->dateDebut, $dto->dateFin);
        if ($conflit !== null) {
            throw new SalleIndisponibleException("La salle est indisponible pendant cette période.");
        }
    }
}