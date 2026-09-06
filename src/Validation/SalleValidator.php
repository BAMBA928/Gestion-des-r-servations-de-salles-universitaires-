<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

final class SalleValidator implements ValidatorInterface
{
    private const TYPES_AUTORISES = ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'];

    public function validate(array $data): ValidationResult
    {
        $errors = [];

        $rules = [
            'nom' => v::stringType()->length(2, 100),
            'batiment' => v::stringType()->length(2, 100),
            'capacite' => v::intVal()->between(1, 1000),
            'type' => v::in(self::TYPES_AUTORISES),
            'active' => v::boolType(),
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