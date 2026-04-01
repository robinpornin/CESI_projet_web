<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

use App\Core\Middleware;

class PageEleve
{
    private \Twig\Environment $twig;
    private PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo  = getPDO();
    }

    public function render(): void
    {
        $jwtUser = Middleware::getUtilisateur();
        $prenom  = $jwtUser?->prenom ?? '';

        echo $this->twig->render('eleve.html.twig', [
            'page'          => 'eleve',
            'title'         => 'Élève',
            'platform_name' => 'CESI-STAGES',
            'prenom'        => $prenom,
            'utilisateur'   => $jwtUser,
            'initiale'      => $prenom[0] ?? '',
        ]);
    }
}
