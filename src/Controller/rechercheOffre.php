<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageRechercheOffre
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
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $countStmt = $this->pdo->query("SELECT COUNT(*) FROM Offres");
        $totalOffres = (int) $countStmt->fetchColumn();
        $totalPages = (int) ceil($totalOffres / $limit);

        $stmt = $this->pdo->prepare("
            SELECT 
                o.ID_Offre,
                o.Titre,
                o.Remuneration,
                o.Date_,
                o.Description,
                o.Duree,
                o.Ville_CP,
                e.Nom_entreprise,
                e.Secteur
            FROM Offres o
            JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            ORDER BY o.Date_ DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $offres = $stmt->fetchAll();

        $stmtVilles = $this->pdo->query("
            SELECT DISTINCT Ville_CP FROM Offres ORDER BY Ville_CP ASC
        ");
        $villes = $stmtVilles->fetchAll(PDO::FETCH_COLUMN);

        echo $this->twig->render('rechercheOffre.html.twig', [
            'page' => 'recherche_offre',
            'title' => 'Recherche Offre',
            'offres' => $offres,
            'villes' => $villes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
