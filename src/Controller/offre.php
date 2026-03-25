<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageOffre
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
        $id = (int) ($_GET['id'] ?? 0);

        // Offre + nom entreprise
        $stmt = $this->pdo->prepare("
            SELECT o.*, e.Nom_entreprise
            FROM Offres o
            JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            WHERE o.ID_Offre = :id
        ");
        $stmt->execute([':id' => $id]);
        $offre = $stmt->fetch();

        // Compétences liées à l'offre
        $stmt = $this->pdo->prepare("
            SELECT c.Nom_competence
            FROM Requerir r
            JOIN Competences c ON r.ID_Competence = c.ID_Competence
            WHERE r.ID_Offre = :id
        ");
        $stmt->execute([':id' => $id]);
        $competences = $stmt->fetchAll();

        // Statistiques
        $stmtCandidatures = $this->pdo->prepare("
            SELECT COUNT(*) FROM Candidatures WHERE ID_Offre = :id
        ");
        $stmtCandidatures->execute([':id' => $id]);
        $nbCandidatures = (int) $stmtCandidatures->fetchColumn();

        $stmtWishlist = $this->pdo->prepare("
            SELECT COUNT(*) FROM Contenir WHERE ID_Offre = :id
        ");
        $stmtWishlist->execute([':id' => $id]);
        $nbWishlist = (int) $stmtWishlist->fetchColumn();

        $tauxWishlist = $nbCandidatures > 0
            ? round(($nbWishlist / $nbCandidatures) * 100)
            : 0;

        echo $this->twig->render('offre.html.twig', [
            'page'        => 'offre',
            'title'       => $offre['Titre'] ?? 'Offre de stage',
            'offre'       => $offre,
            'competences' => $competences,
            'stats'       => [
                'nb_vues'        => 0, // non stocké en BDD pour l'instant
                'nb_candidatures'=> $nbCandidatures,
                'taux_wishlist'  => $tauxWishlist,
            ],
        ]);
    }
}