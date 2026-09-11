<?php

declare(strict_types=1);

namespace App\render;

interface RendererInterface
{
    public function supporte(string $format): bool;

    public function rendre(string $vue, array $donnees): void;

    public function rendreRedirection(string $url): void;
}