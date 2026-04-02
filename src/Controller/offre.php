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

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function render(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $stmt = $this->pdo->prepare("
            SELECT o.*, e.Nom_entreprise, e.ID_Entreprise
            FROM Offres o
            INNER JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            WHERE o.ID_Offre = :id
        ");
        $stmt->execute([':id' => $id]);
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$offre) {
            die('Offre introuvable.');
        }

        $stmt = $this->pdo->prepare("
            SELECT c.Nom_competence
            FROM Requerir r
            INNER JOIN Competences c ON r.ID_Competence = c.ID_Competence
            WHERE r.ID_Offre = :id
            ORDER BY c.Nom_competence ASC
        ");
        $stmt->execute([':id' => $id]);
        $competences = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtCandidatures = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM Candidatures
            WHERE ID_Offre = :id
        ");
        $stmtCandidatures->execute([':id' => $id]);
        $nbCandidatures = (int) $stmtCandidatures->fetchColumn();

        $stmtWishlist = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM Contenir
            WHERE ID_Offre = :id
        ");
        $stmtWishlist->execute([':id' => $id]);
        $nbWishlist = (int) $stmtWishlist->fetchColumn();

        $stmtNote = $this->pdo->prepare("
            SELECT ROUND(AVG(ev.Note), 1)
            FROM Evaluations ev
            WHERE ev.ID_Entreprise = :id_entreprise
        ");
        $stmtNote->execute([
            ':id_entreprise' => $offre['ID_Entreprise']
        ]);
        $noteEntreprise = $stmtNote->fetchColumn();

        $estInvite = empty($_SESSION['utilisateur']['id']);

        $inWishlist = false;

        if (!$estInvite) {
            $inWishlistStmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM Contenir c
                INNER JOIN Wishlists w ON c.ID_Wishlist = w.ID_Wishlist
                WHERE w.ID_Utilisateur = :user 
                  AND c.ID_Offre = :offre
            ");
            $inWishlistStmt->execute([
                ':user' => (int) $_SESSION['utilisateur']['id'],
                ':offre' => $id
            ]);
            $inWishlist = (int) $inWishlistStmt->fetchColumn() > 0;
        }

        echo $this->twig->render('offre.html.twig', [
            'page'        => 'offre',
            'title'       => $offre['Titre'],
            'offre'       => $offre,
            'competences' => $competences,
            'stats'       => [
                'nb_postulations' => $nbCandidatures,
                'nb_wishlist'     => $nbWishlist,
                'note_entreprise' => $noteEntreprise ?: null,
            ],
            'inWishlist'  => $inWishlist,
            'estInvite'   => $estInvite,
        ]);
    }
}

