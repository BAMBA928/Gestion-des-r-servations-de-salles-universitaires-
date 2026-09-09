<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Exception\ReservationIntrouvableException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;

final class ReservationController
{
    public function __construct(
        private ReservationRepositoryInterface $reservations,
        private SalleRepositoryInterface $salles,
        private ReservationValidator $validator,
        private CreerReservationService $creerService,
        private AnnulerReservationService $annulerService,
    ) {
    }

    private function afficher(string $vue, array $donnees = []): void
    {
        extract($donnees);

        ob_start();
        require __DIR__ . '/../../templates/' . $vue . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../templates/layout/base.php';
    }

    public function index(): void
    {
        $salleId = $_GET['salle_id'] ?? null;

        $reservations = $salleId !== null
            ? $this->reservations->listerParSalle((int) $salleId)
            : $this->reservations->lister();

        $this->afficher('reservation/index', ['reservations' => $reservations]);
    }

    public function show(int $id): void
    {
        $reservation = $this->reservations->trouver($id);
        $this->afficher('reservation/show', ['reservation' => $reservation]);
    }

    public function create(): void
    {
        $salles = $this->salles->lister();
        $this->afficher('reservation/form', ['salles' => $salles, 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        $data = $_POST;
        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            $salles = $this->salles->lister();
            $this->afficher('reservation/form', ['salles' => $salles, 'errors' => $resultat->errors(), 'old' => $data]);
            return;
        }

        $dto = CreerReservationDTO::depuisTableau($resultat->data());

        try {
            $reservation = $this->creerService->creer($dto);
        } catch (SalleIndisponibleException $e) {
            $salles = $this->salles->lister();
            $this->afficher('reservation/form', [
                'salles' => $salles,
                'errors' => ['general' => [$e->getMessage()]],
                'old' => $data,
            ]);
            return;
        }

        header('Location: /reservations/' . $reservation->id);
        exit;
    }

    public function cancel(int $id): void
    {
        try {
            $this->annulerService->annuler($id);
        } catch (ReservationIntrouvableException $e) {
            http_response_code(404);
            $this->afficher('error/404');
            return;
        }

        header('Location: /reservations');
        exit;
    }
}