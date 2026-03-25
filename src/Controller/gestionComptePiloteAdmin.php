<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionComptePiloteAdmin
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
        // Role 2 = pilote (à adapter selon ta convention)
        $stmt = $this->pdo->query("
            SELECT ID_Utilisateur, Nom, Prenom, Email
            FROM Utilisateurs
            WHERE Role = 2
            ORDER BY Nom ASC
        ");
        $pilotes = $stmt->fetchAll();

        echo $this->twig->render('gestionComptePilote_admin.html.twig', [
            'page'    => 'gestion_pilote_admin',
            'title'   => 'Gestion des comptes Pilotes',
            'pilotes' => $pilotes,
        ]);
    }
}