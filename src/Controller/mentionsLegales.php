<?php

declare(strict_types=1);

class PageMentionsLegales
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(): void
    {
        // Affiche le template Twig des mentions légales
        echo $this->twig->render('mentionsLegales.html.twig', [
            'page'          => 'mentions_legales',
            'title'         => 'Mentions Légales',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}