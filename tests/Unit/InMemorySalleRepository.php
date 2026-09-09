<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use Illuminate\Support\Collection;

final class InMemorySalleRepository implements SalleRepositoryInterface
{
    private array $salles = [];

    public function ajouter(Salle $salle): void
    {
        $this->salles[$salle->id] = $salle;
    }

    public function lister(): Collection
    {
        return new Collection($this->salles);
    }

    public function trouver(int $id): ?Salle
    {
        return $this->salles[$id] ?? null;
    }

    public function enregistrer(Salle $salle): Salle
    {
        $this->salles[$salle->id] = $salle;

        return $salle;
    }
}