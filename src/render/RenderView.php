<?php

declare(strict_types=1);

namespace App\render;
use App\render\RendererInterface;
final class RenderView
{
    /** @var RendererInterface[] */
    private array $renderers;

    public function __construct(RendererInterface ...$renderers)
    {
        $this->renderers = $renderers;
    }

    public function resoudre(): RendererInterface
    {
        $format = $_ENV['APP_RESPONSE_FORMAT'] ?? 'html';

        foreach ($this->renderers as $renderer) {
            if ($renderer->supporte($format)) {
                return $renderer;
            }
        }

        throw new \RuntimeException("Aucun renderer disponible pour le format « {$format} ».");
    }
}