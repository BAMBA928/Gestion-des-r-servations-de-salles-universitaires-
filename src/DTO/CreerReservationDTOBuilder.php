<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

final class CreerReservationDTOBuilder
{
    private ?int $salleId = null;
    private ?string $responsable = null;
    private ?string $email = null;
    private ?string $motif = null;
    private ?DateTimeImmutable $dateDebut = null;
    private ?DateTimeImmutable $dateFin = null;

    public function avecSalleId(int $salleId): self
    {
        $this->salleId = $salleId;

        return $this;
    }

    public function avecResponsable(string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function avecEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function avecMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function avecDateDebut(DateTimeImmutable $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function avecDateFin(DateTimeImmutable $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

public function construire(): CreerReservationDTO
{
    if ($this->salleId === null
        || $this->responsable === null
        || $this->email === null
        || $this->motif === null
        || $this->dateDebut === null
        || $this->dateFin === null
    ) {
        throw new \LogicException('Tous les champs doivent être renseignés avant de construire le DTO.');
    }

    return CreerReservationDTO::depuisTableau([
        'salle_id' => $this->salleId,
        'responsable' => $this->responsable,
        'email' => $this->email,
        'motif' => $this->motif,
        'date_debut' => $this->dateDebut,
        'date_fin' => $this->dateFin,
    ]);
}
}