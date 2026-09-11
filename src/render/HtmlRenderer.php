<?php

declare(strict_types=1);

namespace App\render;

final class HtmlRenderer implements RendererInterface
{
    public function supporte(string $format): bool
    {
        return $format === 'html';
    }

    public function rendre(string $vue, array $donnees): void
    {
        extract($donnees);

        ob_start();
        require dirname(__DIR__, 2) . '/templates/' . $vue . '.php';
        $content = ob_get_clean();

        require dirname(__DIR__, 2) . '/templates/layout/base.php';
    }

    public function rendreRedirection(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}