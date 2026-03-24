<?php

declare(strict_types=1);

class PageInvite
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
        echo $this->twig->render('creationEntreprise.html.twig', [
            'page'   => 'creation_entreprise',
            'title'  => 'Création d\'une entreprise',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}