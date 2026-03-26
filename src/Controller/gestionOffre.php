<?php
declare(strict_types=1);

class PageGestionOffre
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(): void
    {
        echo $this->twig->render('gestionOffre.html.twig', [
            'page'  => 'gestion_offre',
            'title' => 'Gestion des offres',
        ]);
    }
}