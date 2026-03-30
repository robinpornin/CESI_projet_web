<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageRechercheEntreprise
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
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 5;

        $where = '';
        $params = [];

        if ($search !== '') {
            $where = "WHERE e.Nom_entreprise LIKE :search_nom
                      OR e.Secteur LIKE :search_secteur
                      OR o.Ville_CP LIKE :search_ville";

            $params[':search_nom'] = '%' . $search . '%';
            $params[':search_secteur'] = '%' . $search . '%';
            $params[':search_ville'] = '%' . $search . '%';
        }

        $countSql = "
            SELECT COUNT(DISTINCT e.ID_Entreprise)
            FROM Entreprises e
            LEFT JOIN Offres o ON e.ID_Entreprise = o.ID_Entreprise
            $where
        ";

        $countStmt = $this->pdo->prepare($countSql);

        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $countStmt->execute();
        $totalEntreprises = (int) $countStmt->fetchColumn();
        $totalPages = (int) ceil($totalEntreprises / $limit);

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        if ($page > $totalPages) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT 
                e.ID_Entreprise,
                e.Nom_entreprise,
                e.Secteur,
                e.Type_,
                e.Email_entreprise,
                e.Telephone,
                e.Description_entreprise,
                e.Nb_stagiaires,
                MAX(o.Ville_CP) AS Ville_CP,
                ROUND(AVG(ev.Note), 1) AS Note_moyenne
            FROM Entreprises e
            LEFT JOIN Evaluations ev ON e.ID_Entreprise = ev.ID_Entreprise
            LEFT JOIN Offres o ON e.ID_Entreprise = o.ID_Entreprise
            $where
            GROUP BY e.ID_Entreprise
            ORDER BY e.Nom_entreprise ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->twig->render('rechercheEntreprise.html.twig', [
            'page' => 'recherche_entreprise',
            'title' => 'Recherche Entreprise',
            'entreprises' => $entreprises,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
        ]);
    }
}
