<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

final class SalleValidatorTest extends TestCase
{
    private SalleValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new SalleValidator();
    }

    private function donneesValides(): array
    {
        return [
            'nom' => 'Salle B12',
            'batiment' => 'Bloc B',
            'capacite' => 40,
            'type' => 'cours',
            'active' => true,
        ];
    }

    public function test_donnees_valides_sont_acceptees(): void
    {
        $resultat = $this->validator->validate($this->donneesValides());

        $this->assertTrue($resultat->isValid());
    }

    public function test_capacite_negative_est_rejetee(): void
    {
        $data = $this->donneesValides();
        $data['capacite'] = -5;

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('capacite', $resultat->errors());
    }

    public function test_type_inconnu_est_rejete(): void
    {
        $data = $this->donneesValides();
        $data['type'] = 'bureau';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('type', $resultat->errors());
    }

    public function test_nom_trop_court_est_rejete(): void
    {
        $data = $this->donneesValides();
        $data['nom'] = 'A';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('nom', $resultat->errors());
    }
}