<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        $rules = [
            'salle_id' => v::intVal()->positive(),
            'responsable' => v::stringType()->length(2, 120),
            'email' => v::email(),
            'motif' => v::stringType()->length(5, 255),
            'date_debut' => v::dateTime(),
            'date_fin' => v::dateTime(),
        ];

        foreach ($rules as $champ => $regle) {
            try {
                $regle->assert($data[$champ] ?? null);
            } catch (NestedValidationException $e) {
                $errors[$champ] = $e->getMessages();
            }
        }

        return new ValidationResult(
            empty($errors),
            $errors,
            $data
        );
    }
}