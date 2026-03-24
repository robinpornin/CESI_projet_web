<?php

declare(strict_types=1);

class PageAccueil
{
    private \Twig\Environment $twig;
    

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Récupère les données nécessaires et affiche la page.
     */
    public function render(): void
    {
        // Exemple : récupérer des articles depuis la BDD

        // Passe les données à Twig et affiche
        echo $this->twig->render('accueil.html.twig', [
            'page'     => 'accueil',
            'title'    => 'Accueil',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}