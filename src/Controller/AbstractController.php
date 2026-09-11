<?php

declare(strict_types=1);

namespace App\Controller;

use App\render\RenderView;

abstract class AbstractController
{
    public function __construct(
        private RenderView $rendererResolver,
    ) {
    }

    protected function afficher(string $vue, array $donnees = []): void
    {
        $this->rendererResolver->resoudre()->rendre($vue, $donnees);
    }

    protected function rediriger(string $url): void
    {
        $this->rendererResolver->resoudre()->rendreRedirection($url);
    }
}