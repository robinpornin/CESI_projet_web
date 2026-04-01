<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionEntreprise
{
    private \Twig\Environment $twig;
    private \PDO $pdo;

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
        $entreprises = [];

        if (
            !isset($_SESSION['utilisateur']) ||
            !isset($_SESSION['utilisateur']['id']) ||
            empty($_SESSION['utilisateur']['id'])
        ) {
            $erreur = "Vous devez être connecté pour accéder à la gestion des entreprises.";

            echo $this->twig->render('gestionEntreprise.html.twig', [
                'page' => 'gestion_entreprise',
                'title' => 'Gestion des entreprises',
                'entreprises' => [],
                'erreur' => $erreur,
                'currentPage' => 1,
                'totalPages' => 1,
            ]);
            return;
        }

        $idUtilisateur = (int) $_SESSION['utilisateur']['id'];

        $entreprisesParPage = 2;
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $stmtCount = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM Entreprises
            WHERE ID_Utilisateur = :id_utilisateur
        ");
        $stmtCount->execute([
            ':id_utilisateur' => $idUtilisateur
        ]);
        $totalEntreprises = (int) $stmtCount->fetchColumn();

        $totalPages = max(1, (int) ceil($totalEntreprises / $entreprisesParPage));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $entreprisesParPage;

        $stmt = $this->pdo->prepare("
            SELECT ID_Entreprise, Nom_entreprise, Secteur, Type_
            FROM Entreprises
            WHERE ID_Utilisateur = :id_utilisateur
            ORDER BY Nom_entreprise ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $entreprisesParPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->twig->render('gestionEntreprise.html.twig', [
            'page' => 'gestion_entreprise',
            'title' => 'Gestion des entreprises',
            'entreprises' => $entreprises,
            'erreur' => $erreur,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
}
