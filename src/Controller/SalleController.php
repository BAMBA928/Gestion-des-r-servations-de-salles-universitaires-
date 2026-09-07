<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\Model\Salle;

final class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private SalleValidator $validator,
    ) {
    }

    public function index(): void
    {
        $salles = $this->salles->lister();
        require dirname(__DIR__,2). '/';
    }

    public function show(int $id): void
    {
        $salle = $this->salles->trouver($id);
        require __DIR__ . '/../../templates/salle/show.php';
    }

    public function create(): void
    {
        $errors = [];
        $old = [];
        require __DIR__ . '/../../templates/salle/form.php';
    }

    public function store(): void
    {
        // 1. lire les données HTTP
        $data = $_POST;

        // 2. appeler le validateur
        $resultat = $this->validator->validate($data);

        // 3. réafficher le formulaire en cas d'erreur
        if (!$resultat->isValid()) {
            $errors = $resultat->errors();
            $old = $data;
            require __DIR__ . '/../../templates/salle/form.php';
            return;
        }

        // 4. construire le DTO
        $dto = CreerSalleDTO::depuisTableau($resultat->data());

        // 5. appeler le service (ici, pas de service dédié imposé — on peut enregistrer via le repository)
        $salle = new Salle();
        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type;
        $salle->active = $dto->active;
        $this->salles->enregistrer($salle);

        // 6. rediriger après succès
        header('Location: /salles/' . $salle->id);
        exit;
    }

    public function edit(int $id): void
    {
        $salle = $this->salles->trouver($id);
        $errors = [];
        $old = [];
        require __DIR__ . '/../../templates/salle/form.php';
    }

    public function update(int $id): void
    {
        $data = $_POST;
        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            $salle = $this->salles->trouver($id);
            $errors = $resultat->errors();
            $old = $data;
            require __DIR__ . '/../../templates/salle/form.php';
            return;
        }

        $salle = $this->salles->trouver($id);
        $salle->nom = $data['nom'];
        $salle->batiment = $data['batiment'];
        $salle->capacite = (int) $data['capacite'];
        $salle->type = $data['type'];
        $salle->active = (bool) $data['active'];
        $this->salles->enregistrer($salle);

        header('Location: /salles/' . $salle->id);
        exit;
    }
}