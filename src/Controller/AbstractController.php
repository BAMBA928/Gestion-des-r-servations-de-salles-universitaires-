<?php

declare(strict_types=1);

namespace App\Controller;

abstract class AbstractController
{
    protected function afficher(string $vue, array $donnees = []): void
    {
        extract($donnees);

        ob_start();
        require __DIR__ . '/../../templates/' . $vue . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../templates/layout/base.php';
    }

    protected function rediriger(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}