<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageAdmin
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
        $idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;

        $stmt = $this->pdo->prepare("
            SELECT Nom, Prenom FROM Utilisateurs WHERE ID_Utilisateur = :id
        ");
        $stmt->execute([':id' => $idUtilisateur]);
        $utilisateur = $stmt->fetch();

        echo $this->twig->render('admin.html.twig', [
            'page'        => 'admin',
            'title'       => 'Admin',
            'utilisateur' => $utilisateur,
            'app_user'    => AppUser::fromSession(),
        ]);
    }
}