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

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function render(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $message = null;
        $erreur = null;

        if ($id <= 0) {
            die("Entreprise introuvable.");
        }

        $idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;
        $estConnecte = $idUtilisateur !== null;
        $aDejaNote = false;

        if ($estConnecte) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM Evaluations
                WHERE ID_Entreprise = :idEntreprise
                  AND ID_Utilisateur = :idUtilisateur
            ");
            $stmt->execute([
                ':idEntreprise' => $id,
                ':idUtilisateur' => $idUtilisateur,
            ]);
            $aDejaNote = (int) $stmt->fetchColumn() > 0;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                http_response_code(403);
                die('Requête invalide.');
            }
            
            $note = (int) ($_POST['note'] ?? 0);

            if (!$estConnecte) {
                $erreur = "Vous devez être connecté pour laisser un avis.";
            } elseif ($aDejaNote) {
                $erreur = "Vous avez déjà noté cette entreprise.";
            } elseif ($note < 1 || $note > 5) {
                $erreur = "Choisissez une note entre 1 et 5.";
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO Evaluations (ID_Entreprise, ID_Utilisateur, Note)
                    VALUES (:idEntreprise, :idUtilisateur, :note)
                ");
                $stmt->execute([
                    ':idEntreprise' => $id,
                    ':idUtilisateur' => $idUtilisateur,
                    ':note' => $note,
                ]);

                $message = "Avis envoyé.";
                $aDejaNote = true;
            }
        }

        $stmt = $this->pdo->prepare("
            SELECT * FROM Entreprises WHERE ID_Entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$entreprise) {
            die("Entreprise introuvable.");
        }

        $stmt = $this->pdo->prepare("
            SELECT Titre, Ville_CP, Remuneration, Duree
            FROM Offres
            WHERE ID_Entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare("
            SELECT e.Note, u.Nom, u.Prenom
            FROM Evaluations e
            JOIN Utilisateurs u ON e.ID_Utilisateur = u.ID_Utilisateur
            WHERE e.ID_Entreprise = :id
            ORDER BY e.ID_Evaluation DESC
        ");
        $stmt->execute([':id' => $id]);
        $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->twig->render('ficheEntreprise.html.twig', [
            'page'        => 'fiche_entreprise',
            'title'       => 'Fiche entreprise',
            'entreprise'  => $entreprise,
            'offres'      => $offres,
            'evaluations' => $evaluations,
            'message'     => $message,
            'erreur'      => $erreur,
            'estConnecte' => $estConnecte,
            'aDejaNote'   => $aDejaNote,
        ]);
    }
}
