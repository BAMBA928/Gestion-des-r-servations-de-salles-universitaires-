<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function lister(): Collection
    {
        return Reservation::all();
    }

    public function listerParSalle(int $salleId): Collection
    {
        return Reservation::where('salle_id', $salleId)->get();
    }

    public function trouver(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function rechercherConflit(int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): ?Reservation
    {
        return Reservation::where('salle_id', $salleId)
            ->where('statut', 'confirmée')
            ->where('date_debut', '<', $fin)
            ->where('date_fin', '>', $debut)
            ->first();
    }

    public function enregistrer(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulée';
        $reservation->save();

        return $reservation;
    }

    public function listerPagine(int $page, int $parPage): LengthAwarePaginator
{
    return Reservation::paginate($parPage, ['*'], 'page', $page);
}
}