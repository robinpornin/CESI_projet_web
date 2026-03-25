<?php
declare(strict_types=1);

class PageGestionEntreprise
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(): void
    {
        echo $this->twig->render('gestionEntreprise.html.twig', [
            'page'  => 'gestion_entreprise',
            'title' => 'Gestion des entreprises',
        ]);
    }
}