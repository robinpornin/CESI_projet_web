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
        echo $this->twig->render('formulaire.html.twig', [
            'page'   => 'formulaire',
            'title'  => 'Formulaire',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}