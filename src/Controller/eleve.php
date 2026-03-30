<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageEleve
{
    private \Twig\Environment $twig;
    private PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo  = getPDO();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function render(): void
    {
        $utilisateurSession = $_SESSION['utilisateur'] ?? null;
        $prenom = $utilisateurSession['prenom'] ?? '';

        echo $this->twig->render('eleve.html.twig', [
            'page'          => 'eleve',
            'title'         => 'Élève',
            'platform_name' => 'CESI-STAGES',
            'prenom'        => $utilisateur['Prenom'] ?? $prenom,
            'utilisateur'   => $utilisateurSession,
        ]);
    }
}
