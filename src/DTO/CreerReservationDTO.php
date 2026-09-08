<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

final class CreerReservationDTO
{
    private function __construct(
        public readonly int $salleId,
        public readonly string $responsable,
        public readonly string $email,
        public readonly string $motif,
        public readonly DateTimeImmutable $dateDebut,
        public readonly DateTimeImmutable $dateFin,
    ) {
    }

    public static function depuisTableau(array $data): self
    {
        return new self(
            salleId: (int) $data['salle_id'],
            responsable: $data['responsable'],
            email: $data['email'],
            motif: $data['motif'],
            dateDebut: $data['date_debut'] instanceof DateTimeImmutable
                ? $data['date_debut']
                : new DateTimeImmutable($data['date_debut']),
            dateFin: $data['date_fin'] instanceof DateTimeImmutable
                ? $data['date_fin']
                : new DateTimeImmutable($data['date_fin']),
        );
    }}