<?php
declare(strict_types=1);

class PageParametreOffre
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(): void
    {
        echo $this->twig->render('parametreOffre.html.twig', [
            'page'  => 'parametre_offre',
            'title' => 'Paramètres de l\'offre',
        ]);
    }
}