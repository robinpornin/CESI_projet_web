<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionOffre
{
    private \Twig\Environment $twig;
    private PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo = getPDO();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function render(): void
    {
        $erreur = null;
        $offres = [];

        if (
            !isset($_SESSION['utilisateur']) ||
            !isset($_SESSION['utilisateur']['id']) ||
            empty($_SESSION['utilisateur']['id'])
        ) {
            $erreur = "Vous devez être connecté pour accéder à la gestion des offres.";

            echo $this->twig->render('gestionOffre.html.twig', [
                'page' => 'gestion_offre',
                'title' => 'Gestion des offres',
                'offres' => [],
                'erreur' => $erreur,
                'currentPage' => 1,
                'totalPages' => 1,
            ]);
            return;
        }

        $idUtilisateur = (int) $_SESSION['utilisateur']['id'];
        $offresParPage = 2;
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $stmtCount = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM Offres
            WHERE ID_Utilisateur = :id_utilisateur
        ");
        $stmtCount->execute([
            ':id_utilisateur' => $idUtilisateur
        ]);
        $totalOffres = (int) $stmtCount->fetchColumn();

        $totalPages = max(1, (int) ceil($totalOffres / $offresParPage));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $offresParPage;

        $stmt = $this->pdo->prepare("
            SELECT 
                o.ID_Offre,
                o.Titre,
                o.Remuneration,
                o.Date_,
                o.Ville_CP,
                e.Nom_entreprise
            FROM Offres o
            INNER JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            WHERE o.ID_Utilisateur = :id_utilisateur
            ORDER BY o.Titre ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $offresParPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->twig->render('gestionOffre.html.twig', [
            'page' => 'gestion_offre',
            'title' => 'Gestion des offres',
            'offres' => $offres,
            'erreur' => $erreur,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
}
