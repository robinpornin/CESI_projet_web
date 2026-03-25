<?php

declare(strict_types=1);

class PageCreationEleve
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
        echo $this->twig->render('creationEleve.html.twig', [
            'page'   => 'creation_eleve',
            'title'  => 'Création d\'un élève',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}