<?php

declare(strict_types=1);

namespace App\DTO;

use bool;

final class CreerSalleDTOBuilder
{
   
    private ?string $nom  = null;
    private ?string $batiment = null;
    private ?int $capacite = null;
    private ?string $type = null;
    private ?bool $active = null;

    public function nom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function avecBatiment(string $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function aveccapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function avectype(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function avecactive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }



public function construire(): CreerReservationDTO
{
    if ($this->nom === null
        || $this->batiment === null
        || $this->capacite === null
        || $this->type === null
        || $this->active === null
    ) {
        throw new \LogicException('Tous les champs doivent être renseignés avant de construire le DTO.');
    }

    return CreerReservationDTO::depuisTableau([
        'salle_id' => $this->nom,
        'batiment' => $this->batiment,
        'capacite' => $this->capacite,
        'type' => $this->type,
        'date_debut' => $this->active,
    ]);
}
}