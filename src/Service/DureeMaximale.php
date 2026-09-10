<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Exception\SalleIndisponibleException;

class DureeMaximale implements ReservationInterface
{
        private const DUREE_MAX_HEURES = 4;


    public function verifier(CreerReservationDTO $dto, Salle $salle, ReservationRepositoryInterface $reservationRepository): void
    {
         $dureeEnHeures = ($dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp()) / 3600;
        if ($dureeEnHeures > self::DUREE_MAX_HEURES) {
            throw new SalleIndisponibleException("Une réservation ne peut pas dépasser quatre heures.");
        }
    }
}