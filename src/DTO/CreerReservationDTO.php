<?php

declare(strict_types=1);

namespace App\DTO;


readonly final class CreerReservationDTO
{
    public function __construct(
        public  int $salleId,
        public  string $responsable,
        public  string $email,
        public  string $motif,
        public  \DateTimeImmutable $dateDebut,
        public  \DateTimeImmutable $dateFin,
    ) {
    }

    public static function depuisTableau(array $data): self
    {
        return new self(
            salleId: (int) $data['salle_id'],
            responsable: $data['responsable'],
            email: $data['email'],
            motif: $data['motif'],
            dateDebut: new \DateTimeImmutable($data['date_debut']),
            dateFin: new \DateTimeImmutable($data['date_fin']),
        );
    }
}