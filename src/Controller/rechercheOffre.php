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
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $selectedVille = isset($_GET['ville']) ? trim($_GET['ville']) : '';
        $selectedDuree = isset($_GET['duree']) ? trim($_GET['duree']) : '';
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $limit = 5;
        $whereParts = [];
        $params = [];

        if ($search !== '') {
            $whereParts[] = "(o.Titre LIKE :search_titre
                              OR e.Nom_entreprise LIKE :search_entreprise
                              OR e.Secteur LIKE :search_secteur)";
            $params[':search_titre'] = '%' . $search . '%';
            $params[':search_entreprise'] = '%' . $search . '%';
            $params[':search_secteur'] = '%' . $search . '%';
        }

        if ($selectedVille !== '') {
            $whereParts[] = "o.Ville_CP = :ville";
            $params[':ville'] = $selectedVille;
        }

        if ($selectedDuree !== '') {
            $whereParts[] = "o.Duree = :duree";
            $params[':duree'] = (int) $selectedDuree;
        }

        $whereSql = '';
        if (!empty($whereParts)) {
            $whereSql = 'WHERE ' . implode(' AND ', $whereParts);
        }

        $countSql = "
            SELECT COUNT(*)
            FROM Offres o
            JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            $whereSql
        ";

        $countStmt = $this->pdo->prepare($countSql);

        foreach ($params as $key => $value) {
            if ($key === ':duree') {
                $countStmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $countStmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $countStmt->execute();
        $totalOffres = (int) $countStmt->fetchColumn();
        $totalPages = (int) ceil($totalOffres / $limit);

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        if ($page > $totalPages) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $sql = "
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
            $whereSql
            ORDER BY o.Date_ DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':duree') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtVilles = $this->pdo->query("
            SELECT DISTINCT Ville_CP
            FROM Offres
            ORDER BY Ville_CP ASC
        ");
        $villes = $stmtVilles->fetchAll(PDO::FETCH_COLUMN);

        echo $this->twig->render('rechercheOffre.html.twig', [
            'page' => 'recherche_offre',
            'title' => 'Recherche Offre',
            'offres' => $offres,
            'villes' => $villes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'selectedVille' => $selectedVille,
            'selectedDuree' => $selectedDuree,
        ]);
    }
}
