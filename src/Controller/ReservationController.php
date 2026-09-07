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

    public function index(): void
    {
        $salleId = $_GET['salle_id'] ?? null;

        $reservations = $salleId !== null
            ? $this->reservations->listerParSalle((int) $salleId)
            : $this->reservations->lister();

        require __DIR__ . '/../../templates/reservation/index.php';
    }

    public function show(int $id): void
    {
        $reservation = $this->reservations->trouver($id);
        require __DIR__ . '/../../templates/reservation/show.php';
    }

    public function create(): void
    {
        $salles = $this->salles->lister();
        $errors = [];
        $old = [];
        require __DIR__ . '/../../templates/reservation/form.php';
    }

    public function store(): void
    {
        // 1. lire les données HTTP
        $data = $_POST;

        // 2. appeler le validateur
        $resultat = $this->validator->validate($data);

        // 3. réafficher le formulaire en cas d'erreur
        if (!$resultat->isValid()) {
            $salles = $this->salles->lister();
            $errors = $resultat->errors();
            $old = $data;
            require __DIR__ . '/../../templates/reservation/form.php';
            return;
        }

        // 4. construire le DTO
        $dto = CreerReservationDTO::depuisTableau($resultat->data());

        // 5. appeler le service
        try {
            $reservation = $this->creerService->creer($dto);
        } catch (SalleIndisponibleException $e) {
            $salles = $this->salles->lister();
            $errors = ['general' => [$e->getMessage()]];
            $old = $data;
            require __DIR__ . '/../../templates/reservation/form.php';
            return;
        }

        // 6. rediriger après succès
        header('Location: /reservations/' . $reservation->id);
        exit;
    }

    public function cancel(int $id): void
    {
        try {
            $this->annulerService->annuler($id);
        } catch (ReservationIntrouvableException $e) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        header('Location: /reservations');
        exit;
    }
}