<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\Model\Salle;

final class SalleController extends AbstractController
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private SalleValidator $validator,
    ) {
    }

    public function index(): void
    {
        $salles = $this->salles->lister();
        $this->afficher('salle/index', ['salles' => $salles]);
    }

    public function show(int $id): void
    {
        $salle = $this->salles->trouver($id);
        $this->afficher('salle/show', ['salle' => $salle]);
    }

    public function create(): void
    {
        $this->afficher('salle/form', ['errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        $data = $_POST;
        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            $this->afficher('salle/form', ['errors' => $resultat->errors(), 'old' => $data]);
            return;
        }

        $dto = CreerSalleDTO::depuisTableau($resultat->data());

        $salle = new Salle();
        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type;
        $salle->active = $dto->active;
        $this->salles->enregistrer($salle);

        $this->rediriger('/salles/' . $salle->id);
    }

    public function edit(int $id): void
    {
        $salle = $this->salles->trouver($id);
        $this->afficher('salle/form', ['salle' => $salle, 'errors' => [], 'old' => []]);
    }

    public function update(int $id): void
    {
        $data = $_POST;
        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            $salle = $this->salles->trouver($id);
            $this->afficher('salle/form', ['salle' => $salle, 'errors' => $resultat->errors(), 'old' => $data]);
            return;
        }

        $salle = $this->salles->trouver($id);
        $salle->nom = $data['nom'];
        $salle->batiment = $data['batiment'];
        $salle->capacite = (int) $data['capacite'];
        $salle->type = $data['type'];
        $salle->active = (bool) $data['active'];
        $this->salles->enregistrer($salle);

        $this->rediriger('/salles/' . $salle->id);
    }
}