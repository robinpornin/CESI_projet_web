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
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $countStmt = $this->pdo->query("SELECT COUNT(*) FROM Entreprises");
        $totalEntreprises = (int) $countStmt->fetchColumn();
        $totalPages = (int) ceil($totalEntreprises / $limit);

        $stmt = $this->pdo->prepare("
            SELECT 
                e.ID_Entreprise,
                e.Nom_entreprise,
                e.Secteur,
                e.Type_,
                e.Email_entreprise,
                e.Telephone,
                e.Description_entreprise,
                e.Nb_stagiaires,
                ROUND(AVG(ev.Note), 1) AS Note_moyenne
            FROM Entreprises e
            LEFT JOIN Evaluations ev ON e.ID_Entreprise = ev.ID_Entreprise
            GROUP BY e.ID_Entreprise
            ORDER BY e.Nom_entreprise ASC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $entreprises = $stmt->fetchAll();

        echo $this->twig->render('rechercheEntreprise.html.twig', [
            'page' => 'recherche_entreprise',
            'title' => 'Recherche Entreprise',
            'entreprises' => $entreprises,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
