<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageParametreEntreprise
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
            echo $this->twig->render('parametreEntreprise.html.twig', [
                'page'       => 'parametre_entreprise',
                'title'      => 'Paramètres de l\'entreprise',
                'entreprise' => null,
                'erreur'     => 'Vous devez être connecté pour accéder à cette page.',
                'csrf_token' => $_SESSION['csrf_token'],
            ]);
            return;
        }

        $idUtilisateur = (int) $_SESSION['utilisateur']['id'];
        $id = (int) ($_GET['id'] ?? $_POST['id_entreprise'] ?? 0);
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
                SELECT ID_Entreprise
                FROM Entreprises
                WHERE ID_Entreprise = :id
                  AND ID_Utilisateur = :id_utilisateur
            ");
            $stmtCheck->execute([
                ':id' => $id,
                ':id_utilisateur' => $idUtilisateur,
            ]);

            $entrepriseCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$entrepriseCheck) {
                $erreur = 'Entreprise introuvable ou accès non autorisé.';
            } else {
                try {
                    $this->pdo->beginTransaction();

                    $stmtIdsOffres = $this->pdo->prepare("
                        SELECT ID_Offre
                        FROM Offres
                        WHERE ID_Entreprise = :id_entreprise
                    ");
                    $stmtIdsOffres->execute([
                        ':id_entreprise' => $id
                    ]);
                    $idsOffres = $stmtIdsOffres->fetchAll(PDO::FETCH_COLUMN);

                    if (!empty($idsOffres)) {
                        $placeholders = implode(',', array_fill(0, count($idsOffres), '?'));

                        $stmtCandidatures = $this->pdo->prepare("
                            DELETE FROM Candidatures
                            WHERE ID_Offre IN ($placeholders)
                        ");
                        $stmtCandidatures->execute($idsOffres);

                        $stmtContenir = $this->pdo->prepare("
                            DELETE FROM Contenir
                            WHERE ID_Offre IN ($placeholders)
                        ");
                        $stmtContenir->execute($idsOffres);

                        $stmtRequerir = $this->pdo->prepare("
                            DELETE FROM Requerir
                            WHERE ID_Offre IN ($placeholders)
                        ");
                        $stmtRequerir->execute($idsOffres);

                        $stmtOffres = $this->pdo->prepare("
                            DELETE FROM Offres
                            WHERE ID_Offre IN ($placeholders)
                        ");
                        $stmtOffres->execute($idsOffres);
                    }

                    $stmtEvaluations = $this->pdo->prepare("
                        DELETE FROM Evaluations
                        WHERE ID_Entreprise = :id_entreprise
                    ");
                    $stmtEvaluations->execute([
                        ':id_entreprise' => $id
                    ]);

                    $stmtEntreprise = $this->pdo->prepare("
                        DELETE FROM Entreprises
                        WHERE ID_Entreprise = :id
                          AND ID_Utilisateur = :id_utilisateur
                    ");
                    $stmtEntreprise->execute([
                        ':id' => $id,
                        ':id_utilisateur' => $idUtilisateur,
                    ]);

                    $this->pdo->commit();

                    header('Location: /gestionEntreprise');
                    exit;
                } catch (\PDOException $e) {
                    if ($this->pdo->inTransaction()) {
                        $this->pdo->rollBack();
                    }

                    $erreur = "Erreur lors de la suppression de l'entreprise : " . $e->getMessage();
                }
            }
        }

        $stmt = $this->pdo->prepare("
            SELECT ID_Entreprise, Nom_entreprise, Secteur, Type_
            FROM Entreprises
            WHERE ID_Entreprise = :id
              AND ID_Utilisateur = :id_utilisateur
        ");
        $stmt->execute([
            ':id' => $id,
            ':id_utilisateur' => $idUtilisateur,
        ]);
        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$entreprise && $erreur === null) {
            $erreur = 'Entreprise introuvable ou accès non autorisé.';
        }

        echo $this->twig->render('parametreEntreprise.html.twig', [
            'page'       => 'parametre_entreprise',
            'title'      => 'Paramètres de l\'entreprise',
            'entreprise' => $entreprise,
            'erreur'     => $erreur,
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }
}
