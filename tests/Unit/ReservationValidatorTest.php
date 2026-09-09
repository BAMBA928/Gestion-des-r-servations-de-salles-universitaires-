<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validation\ReservationValidator;
use PHPUnit\Framework\TestCase;

final class ReservationValidatorTest extends TestCase
{
    private ReservationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ReservationValidator();
    }

    private function donneesValides(): array
    {
        return [
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@universite.sn',
            'motif' => 'Cours d\'architecture logicielle',
            'date_debut' => '2026-09-10 10:00',
            'date_fin' => '2026-09-10 12:00',
        ];
    }

    public function test_donnees_valides_sont_acceptees(): void
    {
        $resultat = $this->validator->validate($this->donneesValides());

        $this->assertTrue($resultat->isValid());
    }

    public function test_email_invalide_est_rejete(): void
    {
        $data = $this->donneesValides();
        $data['email'] = 'email-invalide';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('email', $resultat->errors());
    }

    public function test_responsable_vide_est_rejete(): void
    {
        $data = $this->donneesValides();
        $data['responsable'] = '';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('responsable', $resultat->errors());
    }

    public function test_motif_trop_court_est_rejete(): void
    {
        $data = $this->donneesValides();
        $data['motif'] = 'TP';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('motif', $resultat->errors());
    }

    public function test_date_incorrecte_est_rejetee(): void
    {
        $data = $this->donneesValides();
        $data['date_debut'] = 'pas-une-date';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('date_debut', $resultat->errors());
    }
}