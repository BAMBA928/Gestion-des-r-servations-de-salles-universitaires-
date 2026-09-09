<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult
{
    private bool $valid;
    private array $errors;
    private array $data;

    public function __construct(bool $valid, array $errors, array $data)
    {
        $this->valid = $valid;
        $this->errors = $errors;
        $this->data = $data;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->data;
    }
}