<?php

declare(strict_types=1);

class PageEspaceEleve
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
        echo $this->twig->render('espaceEleve.html.twig', [
            'page'   => 'espace_eleve',
            'title'  => 'Espace élève',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}