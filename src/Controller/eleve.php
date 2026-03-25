<?php

declare(strict_types=1);

class PageEleve
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Récupère les données et affiche la page invité.
     */
    public function render(): void
    {
        echo $this->twig->render('eleve.html.twig', [
            'page'   => 'eleve',
            'title'  => 'élève',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}