<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageParametreOffre
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
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if (
            !isset($_SESSION['utilisateur']) ||
            !isset($_SESSION['utilisateur']['id']) ||
            empty($_SESSION['utilisateur']['id'])
        ) {
            echo $this->twig->render('parametreOffre.html.twig', [
                'page'       => 'parametre_offre',
                'title'      => 'Paramètres de l\'offre',
                'offre'      => null,
                'erreur'     => 'Vous devez être connecté pour accéder à cette page.',
                'csrf_token' => $_SESSION['csrf_token'],
            ]);
            return;
        }

        $idUtilisateur = (int) $_SESSION['utilisateur']['id'];
        $id = (int) ($_GET['id'] ?? $_POST['id_offre'] ?? 0);
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $stmtCheck = $this->pdo->prepare("
                SELECT ID_Offre
                FROM Offres
                WHERE ID_Offre = :id
                  AND ID_Utilisateur = :id_utilisateur
            ");
            $stmtCheck->execute([
                ':id' => $id,
                ':id_utilisateur' => $idUtilisateur,
            ]);

            $offreCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$offreCheck) {
                $erreur = 'Offre introuvable ou accès non autorisé.';
            } else {
                try {
                    $this->pdo->beginTransaction();

                    $stmtCandidatures = $this->pdo->prepare("
                        DELETE FROM Candidatures
                        WHERE ID_Offre = :id_offre
                    ");
                    $stmtCandidatures->execute([
                        ':id_offre' => $id
                    ]);

                    $stmtContenir = $this->pdo->prepare("
                        DELETE FROM Contenir
                        WHERE ID_Offre = :id_offre
                    ");
                    $stmtContenir->execute([
                        ':id_offre' => $id
                    ]);

                    $stmtRequerir = $this->pdo->prepare("
                        DELETE FROM Requerir
                        WHERE ID_Offre = :id_offre
                    ");
                    $stmtRequerir->execute([
                        ':id_offre' => $id
                    ]);

                    $stmtOffre = $this->pdo->prepare("
                        DELETE FROM Offres
                        WHERE ID_Offre = :id
                          AND ID_Utilisateur = :id_utilisateur
                    ");
                    $stmtOffre->execute([
                        ':id' => $id,
                        ':id_utilisateur' => $idUtilisateur,
                    ]);

                    $this->pdo->commit();

                    header('Location: /gestionOffre');
                    exit;
                } catch (\PDOException $e) {
                    if ($this->pdo->inTransaction()) {
                        $this->pdo->rollBack();
                    }

                    $erreur = "Erreur lors de la suppression de l'offre : " . $e->getMessage();
                }
            }
        }

        $stmt = $this->pdo->prepare("
            SELECT 
                o.ID_Offre,
                o.Titre,
                o.Description,
                o.Remuneration,
                o.Date_,
                o.Duree,
                o.Ville_CP,
                e.Nom_entreprise
            FROM Offres o
            INNER JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
            WHERE o.ID_Offre = :id
              AND o.ID_Utilisateur = :id_utilisateur
        ");
        $stmt->execute([
            ':id' => $id,
            ':id_utilisateur' => $idUtilisateur,
        ]);
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$offre && $erreur === null) {
            $erreur = 'Offre introuvable ou accès non autorisé.';
        }

        echo $this->twig->render('parametreOffre.html.twig', [
            'page'       => 'parametre_offre',
            'title'      => 'Paramètres de l\'offre',
            'offre'      => $offre,
            'erreur'     => $erreur,
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }
}
