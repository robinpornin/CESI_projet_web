<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageListeCandidaturesPilote
{
    private \Twig\Environment $twig;
    private \PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo  = getPDO();
    }

    public function render(): void
    {
        // Récupère l'ID de l'étudiant passé en paramètre GET
        $idEtudiant = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($idEtudiant <= 0) {
            header('Location: /gestionCompteElevePilote');
            exit;
        }

        // Récupère les infos de l'étudiant
        $stmtUser = $this->pdo->prepare("
            SELECT Nom, Prenom, Email
            FROM Utilisateurs
            WHERE ID_Utilisateur = :id AND Role = 1
        ");
        $stmtUser->execute(['id' => $idEtudiant]);
        $etudiant = $stmtUser->fetch(\PDO::FETCH_ASSOC);

        if (!$etudiant) {
            header('Location: /gestionCompteElevePilote');
            exit;
        }

        // Récupère les candidatures de l'étudiant
        $sql = "
            SELECT
                c.ID_Candidature,
                c.CV,
                c.Lettre_de_motivation,
                o.Titre,
                o.Date_,
                o.Ville_CP,
                e.Nom_entreprise
            FROM Candidatures c
            JOIN Offres o      ON c.ID_Offre      = o.ID_Offre
            JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            WHERE c.ID_Utilisateur = :id
            ORDER BY o.Date_ DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $idEtudiant]);
        $candidatures = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo $this->twig->render('listeCandidaturesPilote.html.twig', [
            'etudiant'     => $etudiant,
            'candidatures' => $candidatures,
        ]);
    }
}