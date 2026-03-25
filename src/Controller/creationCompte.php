<?php

declare(strict_types=1);

class PageCreationCompte
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
        echo $this->twig->render('creationCompte.html.twig', [
            'page'   => 'creation_compte',
            'title'  => 'Création de compte',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}