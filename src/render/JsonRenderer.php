<?php

declare(strict_types=1);

namespace App\render;

final class JsonRenderer implements RendererInterface
{
    public function supporte(string $format): bool
    {
        return $format === 'json';
    }

    public function rendre(string $vue, array $donnees): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function rendreRedirection(string $url): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(201);
        echo json_encode(['location' => $url], JSON_PRETTY_PRINT);
    }
}