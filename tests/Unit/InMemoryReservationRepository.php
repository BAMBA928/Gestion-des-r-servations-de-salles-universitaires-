<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


final class InMemoryReservationRepository implements ReservationRepositoryInterface
{
    private array $reservations = [];
    private int $prochainId = 1;

    public function lister(): Collection
    {
        return new Collection($this->reservations);
    }

    public function listerParSalle(int $salleId): Collection
    {
        return new Collection(
            array_filter($this->reservations, fn (Reservation $r) => $r->salle_id === $salleId)
        );
    }

    public function trouver(int $id): ?Reservation
    {
        return $this->reservations[$id] ?? null;
    }

    public function rechercherConflit(int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): ?Reservation
    {
        foreach ($this->reservations as $reservation) {
            if ($reservation->salle_id !== $salleId) {
                continue;
            }
            if ($reservation->statut !== 'confirmée') {
                continue;
            }
            if ($debut < $reservation->date_fin && $fin > $reservation->date_debut) {
                return $reservation;
            }
        }

        return null;
    }

    public function enregistrer(Reservation $reservation): Reservation
    {
        if ($reservation->id === null) {
            $reservation->id = $this->prochainId++;
        }
        $this->reservations[$reservation->id] = $reservation;

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulée';

        return $this->enregistrer($reservation);
    }
public function listerPagine(int $page, int $parPage): LengthAwarePaginator
{
    $tous = array_values($this->reservations);
    $total = count($tous);
    $offset = ($page - 1) * $parPage;
    $items = array_slice($tous, $offset, $parPage);

    return new LengthAwarePaginator($items, $total, $parPage, $page);
}
}