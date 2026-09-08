<?php

declare(strict_types=1);

namespace App\DTO;

final class CreerSalleDTO
{
    private function __construct(
        public readonly string $nom,
        public readonly string $batiment,
        public readonly int $capacite,
        public readonly string $type,
        public readonly bool $active,
    ) {
    }

    public static function depuisTableau(array $data): self
    {
        return new self(
            nom: $data['nom'],
            batiment: $data['batiment'],
            capacite: (int) $data['capacite'],
            type: $data['type'],
            active: (bool) $data['active'],
        );
    }
}