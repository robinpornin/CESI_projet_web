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
        echo $this->twig->render('invite.html.twig', [
            'page'   => 'guest',
            'title'  => 'Invités',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}