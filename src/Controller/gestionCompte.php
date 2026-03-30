<?php
declare(strict_types=1);

require_once __DIR__ . '/appUser.php';
require_once __DIR__ . '/../../database.php';

class PageGestionCompte
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
            SELECT Nom, Prenom, Email FROM Utilisateurs WHERE ID_Utilisateur = :id
        ");
        $stmt->execute([':id' => $idUtilisateur]);
        $utilisateur = $stmt->fetch();

        echo $this->twig->render('gestionCompte.html.twig', [
            'page'        => 'gestion_compte',
            'title'       => 'Gestion de votre compte',
            'utilisateur' => $utilisateur,
            'app_user'      => AppUser::fromSession(),
        ]);
    }
}