<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionCompteEleveAdmin
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
        $stmt = $this->pdo->query("
            SELECT ID_Utilisateur, Nom, Prenom, Email
            FROM Utilisateurs
            WHERE Role = 1
            ORDER BY Nom ASC
        ");
        $etudiants = $stmt->fetchAll();

        echo $this->twig->render('gestionCompteEleve_admin.html.twig', [
            'page'      => 'gestion_eleve_admin',
            'title'     => 'Gestion des comptes Étudiants',
            'etudiants' => $etudiants,
            'app_user'  => AppUser::fromSession(),
        ]);
    }
}