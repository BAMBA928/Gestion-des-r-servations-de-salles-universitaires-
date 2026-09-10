<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

final class CreerReservationService
{

    public function __construct(
        private SalleRepositoryInterface $salles,
        private ReservationRepositoryInterface $reservations,
        private array $Exceptions

    ) {
    }

    public  function creer(CreerReservationDTO $dto): Reservation
    {
        $salle = $this->salles->trouver($dto->salleId);
        if ($salle === null) {
            throw new SalleIndisponibleException("La salle n'existe pas.");
        }

        foreach ($this->Exceptions as $Exception) {
            $Exception->verifier($dto, $salle, $this->reservations);
        }


        $reservation = new Reservation();
        $reservation->salle_id = $dto->salleId;
        $reservation->responsable = $dto->responsable;
        $reservation->email = $dto->email;
        $reservation->motif = $dto->motif;
        $reservation->date_debut = $dto->dateDebut;
        $reservation->date_fin = $dto->dateFin;
        $reservation->statut = 'confirmée';

        $this->reservations->enregistrer($reservation);

        return $reservation;
    }
}
