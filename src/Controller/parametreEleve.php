<?php
declare(strict_types=1);

class PageParametreEleve
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(): void
    {
        echo $this->twig->render('parametreEleve.html.twig', [
            'page'  => 'parametre_eleve',
            'title' => 'Paramètres du compte Élève',
        ]);
    }
}