<?php

declare(strict_types=1);

use App\Core\Middleware;

class PageAccueil
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(): void
    {
        echo $this->twig->render('accueil.html.twig', [
            'page'          => 'accueil',
            'title'         => 'Accueil',
            'platform_name' => 'CESI-STAGES',
            'utilisateur'   => Middleware::getUtilisateur(),
        ]);
    }
}
