<?php
declare(strict_types=1);
require_once __DIR__ . '/../../database.php';

class PageSuppressionCompte
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function render(): void
    {
        header('Content-Type: application/json');

        // ── Vérification méthode POST ──────────────────────────────────
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            return;
        }

        // ── Vérification session ───────────────────────────────────────
        if (empty($_SESSION['utilisateur']['id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non connecté.']);
            return;
        }

        $idUtilisateur = (int) $_SESSION['utilisateur']['id'];

        try {
            $this->pdo->beginTransaction();

            // ── 1. Récupérer la wishlist de l'utilisateur ──────────────
            $stmtWishlist = $this->pdo->prepare("
                SELECT ID_Wishlist FROM Wishlists
                WHERE ID_Utilisateur = :id
            ");
            $stmtWishlist->execute([':id' => $idUtilisateur]);
            $wishlist = $stmtWishlist->fetch(PDO::FETCH_ASSOC);

            // ── 2. Supprimer les entrées Contenir de sa wishlist ───────
            if ($wishlist) {
                $this->pdo->prepare("
                    DELETE FROM Contenir WHERE ID_Wishlist = :id
                ")->execute([':id' => $wishlist['ID_Wishlist']]);
            }

            // ── 3. Supprimer la Wishlist ───────────────────────────────
            $this->pdo->prepare("
                DELETE FROM Wishlists WHERE ID_Utilisateur = :id
            ")->execute([':id' => $idUtilisateur]);

            // ── 4. Supprimer les Candidatures de l'utilisateur ─────────
            $this->pdo->prepare("
                DELETE FROM Candidatures WHERE ID_Utilisateur = :id
            ")->execute([':id' => $idUtilisateur]);

            // ── 5. Supprimer les Evaluations de l'utilisateur ──────────
            $this->pdo->prepare("
                DELETE FROM Evaluations WHERE ID_Utilisateur = :id
            ")->execute([':id' => $idUtilisateur]);

            // ── 6. Récupérer toutes les offres liées à l'utilisateur ───
            $stmtOffresEntreprises = $this->pdo->prepare("
                SELECT o.ID_Offre
                FROM Offres o
                INNER JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
                WHERE e.ID_Utilisateur = :id
            ");
            $stmtOffresEntreprises->execute([':id' => $idUtilisateur]);
            $offresEntreprises = $stmtOffresEntreprises->fetchAll(PDO::FETCH_COLUMN);

            $stmtOffresDirectes = $this->pdo->prepare("
                SELECT ID_Offre FROM Offres WHERE ID_Utilisateur = :id
            ");
            $stmtOffresDirectes->execute([':id' => $idUtilisateur]);
            $offresDirectes = $stmtOffresDirectes->fetchAll(PDO::FETCH_COLUMN);

            $toutesLesOffres = array_unique(array_merge($offresEntreprises, $offresDirectes));

            if (!empty($toutesLesOffres)) {
                $placeholders = implode(',', array_fill(0, count($toutesLesOffres), '?'));

                // ── 6a. Supprimer Requerir liés à ces offres ──────────
                $this->pdo->prepare("
                    DELETE FROM Requerir WHERE ID_Offre IN ($placeholders)
                ")->execute($toutesLesOffres);

                // ── 6b. Supprimer Contenir liés à ces offres ──────────
                $this->pdo->prepare("
                    DELETE FROM Contenir WHERE ID_Offre IN ($placeholders)
                ")->execute($toutesLesOffres);

                // ── 6c. Supprimer Candidatures liées à ces offres ─────
                $this->pdo->prepare("
                    DELETE FROM Candidatures WHERE ID_Offre IN ($placeholders)
                ")->execute($toutesLesOffres);

                // ── 6d. Supprimer les Offres ──────────────────────────
                $this->pdo->prepare("
                    DELETE FROM Offres WHERE ID_Offre IN ($placeholders)
                ")->execute($toutesLesOffres);
            }

            // ── 7. Supprimer Evaluations liées aux entreprises ─────────
            $this->pdo->prepare("
                DELETE ev FROM Evaluations ev
                INNER JOIN Entreprises e ON ev.ID_Entreprise = e.ID_Entreprise
                WHERE e.ID_Utilisateur = :id
            ")->execute([':id' => $idUtilisateur]);

            // ── 8. Supprimer les Entreprises de l'utilisateur ──────────
            $this->pdo->prepare("
                DELETE FROM Entreprises WHERE ID_Utilisateur = :id
            ")->execute([':id' => $idUtilisateur]);

            // ── 9. Supprimer l'Utilisateur ─────────────────────────────
            $this->pdo->prepare("
                DELETE FROM Utilisateurs WHERE ID_Utilisateur = :id
            ")->execute([':id' => $idUtilisateur]);

            $this->pdo->commit();

            // ── 10. Détruire la session ────────────────────────────────
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();

            echo json_encode(['success' => true]);

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ]);
        }
    }
}