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

        // Nombre de candidatures
        $stmtCandidatures = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM Candidatures 
            WHERE ID_Offre = :id
        ");
        $stmtCandidatures->execute([':id' => $id]);
        $nbCandidatures = (int) $stmtCandidatures->fetchColumn();

        // Nombre d'ajouts à la wishlist
        $stmtWishlist = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM Contenir 
            WHERE ID_Offre = :id
        ");

        $stmtNote = $this->pdo->prepare("
            SELECT ROUND(AVG(note), 1)
            FROM Evaluations
             WHERE ID_Entreprise = :idEntreprise
        ");

$stmtNote->execute([':idEntreprise' => $offre['ID_Entreprise']]);
$noteEntreprise = $stmtNote->fetchColumn();

        $stmtWishlist->execute([':id' => $id]);
        $nbWishlist = (int) $stmtWishlist->fetchColumn();

        echo $this->twig->render('offre.html.twig', [
            'page'        => 'offre',
            'title'       => $offre['Titre'] ?? 'Offre de stage',
            'offre'       => $offre,
            'competences' => $competences,
            'stats'       => [
                'nb_postulations' => $nbCandidatures,
                'nb_wishlist'     => $nbWishlist,
                'note_entreprise' => $noteEntreprise,
            ],
        ]);
    }
}