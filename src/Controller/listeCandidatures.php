<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageListeCandidatures
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
            SELECT
                c.ID_Candidature,
                c.Lettre_de_motivation,
                c.CV,
                o.ID_Offre,
                o.Titre,
                o.Ville_CP,
                o.Remuneration,
                o.Duree,
                e.Nom_entreprise
            FROM Candidatures c
            JOIN Offres o      ON c.ID_Offre      = o.ID_Offre
            JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            WHERE c.ID_Utilisateur = :id
            ORDER BY c.ID_Candidature DESC
        ");
        $stmt->execute([':id' => $idUtilisateur]);
        $candidatures = $stmt->fetchAll();

        $stmtUser = $this->pdo->prepare("
            SELECT Nom, Prenom FROM Utilisateurs WHERE ID_Utilisateur = :id
        ");
        $stmtUser->execute([':id' => $idUtilisateur]);
        $utilisateur = $stmtUser->fetch();

        echo $this->twig->render('listeCandidatures.html.twig', [
            'page'         => 'liste_candidatures',
            'title'        => 'Mes Candidatures',
            'candidatures' => $candidatures,
            'utilisateur'  => $utilisateur,
            'app_user'      => AppUser::fromSession(),
        ]);
    }
}