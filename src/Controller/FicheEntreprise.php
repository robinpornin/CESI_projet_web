<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageFicheEntreprise
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

        // Entreprise
        $stmt = $this->pdo->prepare("
            SELECT * FROM Entreprises WHERE ID_Entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $entreprise = $stmt->fetch();

        // Offres liées
        $stmt = $this->pdo->prepare("
            SELECT Titre, Ville_CP, Remuneration, Duree
            FROM Offres
            WHERE ID_Entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $offres = $stmt->fetchAll();

        // Évaluations avec nom de l'auteur
        $stmt = $this->pdo->prepare("
            SELECT e.Note, u.Nom, u.Prenom
            FROM Evaluations e
            JOIN Utilisateurs u ON e.ID_Utilisateur = u.ID_Utilisateur
            WHERE e.ID_Entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $evaluations = $stmt->fetchAll();

        echo $this->twig->render('ficheEntreprise.html.twig', [
            'page'        => 'fiche_entreprise',
            'title'       => 'Fiche entreprise',
            'entreprise'  => $entreprise,
            'offres'      => $offres,
            'evaluations' => $evaluations,
        ]);
    }
}