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
    private const DUREE_MAX_HEURES = 4;

    public function __construct(
        private SalleRepositoryInterface $salles,
        private ReservationRepositoryInterface $reservations,
    ) {
    }

    public  function creer(CreerReservationDTO $dto): Reservation
    {
        // 1. retrouver la salle
        $salle = $this->salles->trouver($dto->salleId);
        if ($salle === null) {
            throw new SalleIndisponibleException("La salle n'existe pas.");
        }

        // 2. vérifier qu'elle est active
        if (!$salle->active) {
            throw new SalleIndisponibleException("Cette salle ne peut pas être réservée.");
        }

        // 3. vérifier que le début précède la fin
        if ($dto->dateDebut >= $dto->dateFin) {
            throw new SalleIndisponibleException("La date de début doit précéder la date de fin.");
        }

        // 4. vérifier que la durée ne dépasse pas 4 heures
        $dureeEnHeures = ($dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp()) / 3600;
        if ($dureeEnHeures > self::DUREE_MAX_HEURES) {
            throw new SalleIndisponibleException("Une réservation ne peut pas dépasser quatre heures.");
        }

        // 5. vérifier que la date est future
        if ($dto->dateDebut <= new \DateTimeImmutable()) {
            throw new SalleIndisponibleException("La réservation doit commencer dans le futur.");
        }

        // 6. rechercher les chevauchements
        $conflit = $this->reservations->rechercherConflit($dto->salleId, $dto->dateDebut, $dto->dateFin);
        if ($conflit !== null) {
            throw new SalleIndisponibleException("La salle est indisponible pendant cette période.");
        }

        // 7. créer la réservation
        $reservation = new Reservation();
        $reservation->salle_id = $dto->salleId;
        $reservation->responsable = $dto->responsable;
        $reservation->email = $dto->email;
        $reservation->motif = $dto->motif;
        $reservation->date_debut = $dto->dateDebut;
        $reservation->date_fin = $dto->dateFin;
        $reservation->statut = 'confirmée';

        // 8. l'enregistrer
        $this->reservations->enregistrer($reservation);

        // 9. retourner le résultat
        return $reservation;
    }
}