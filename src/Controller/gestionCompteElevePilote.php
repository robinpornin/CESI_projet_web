<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionCompteElevePilote
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
            ORDER BY Nom ASC, Prenom ASC
        ");

        $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->twig->render('gestionCompteEleve_Pilote.html.twig', [
            'page'      => 'gestion_eleve_pilote',
            'title'     => 'Gestion des comptes Étudiants',
            'etudiants' => $etudiants,
            'app_user'  => AppUser::fromSession(),
        ]);
    }
}
