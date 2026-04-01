<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageWishlist
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
        $idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;

        if (!$idUtilisateur) {
            header('Location: /connexion');
            exit;
        }

        $stmt = $this->pdo->prepare("
            SELECT 
                o.ID_Offre,
                o.Titre,
                o.Remuneration,
                o.Duree,
                o.Ville_CP,
                e.Nom_entreprise
            FROM Contenir c
            JOIN Wishlists w    ON c.ID_Wishlist   = w.ID_Wishlist
            JOIN Offres o       ON c.ID_Offre      = o.ID_Offre
            JOIN Entreprises e  ON o.ID_Entreprise = e.ID_Entreprise
            WHERE w.ID_Utilisateur = :id
            ORDER BY o.Titre ASC
        ");
        $stmt->execute([':id' => $idUtilisateur]);
        $offres = $stmt->fetchAll();

        $stmtUser = $this->pdo->prepare("
            SELECT Nom, Prenom FROM Utilisateurs WHERE ID_Utilisateur = :id
        ");
        $stmtUser->execute([':id' => $idUtilisateur]);
        $utilisateur = $stmtUser->fetch();

        echo $this->twig->render('wishlist.html.twig', [
            'page'        => 'wishlist',
            'title'       => 'Ma Wishlist',
            'offres'      => $offres,
            'utilisateur' => $utilisateur,
            'app_user'    => AppUser::fromSession(),
        ]);
    }

    public function ajouter(): void
    {
        header('Content-Type: application/json');

        $headers = getallheaders();
        $csrfToken = $headers['X-CSRF-Token'] ?? '';
        if (empty($csrfToken) || !hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        $idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;

        if (!$idUtilisateur) {
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idOffre = (int) ($data['id_offre'] ?? 0);

        if (!$idOffre) {
            echo json_encode([
                'success' => false,
                'message' => 'ID offre invalide'
            ]);
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT ID_Wishlist
                FROM Wishlists
                WHERE ID_Utilisateur = :id
            ");
            $stmt->execute([':id' => $idUtilisateur]);
            $wishlist = $stmt->fetch();

            if (!$wishlist) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Wishlist introuvable'
                ]);
                exit;
            }

            $idWishlist = $wishlist['ID_Wishlist'];

            $check = $this->pdo->prepare("
                SELECT 1
                FROM Contenir
                WHERE ID_Offre = :offre
                  AND ID_Wishlist = :wishlist
            ");
            $check->execute([
                ':offre'    => $idOffre,
                ':wishlist' => $idWishlist
            ]);

            if ($check->fetch()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Déjà dans la wishlist'
                ]);
                exit;
            }

            $insert = $this->pdo->prepare("
                INSERT INTO Contenir (ID_Offre, ID_Wishlist)
                VALUES (:offre, :wishlist)
            ");
            $insert->execute([
                ':offre'    => $idOffre,
                ':wishlist' => $idWishlist
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Offre ajoutée à la wishlist'
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur'
            ]);
        }
    }

    public function supprimer(): void
    {
        header('Content-Type: application/json');

        $headers = getallheaders();
        $csrfToken = $headers['X-CSRF-Token'] ?? '';
        if (empty($csrfToken) || !hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        $idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;

        if (!$idUtilisateur) {
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idOffre = (int) ($data['id_offre'] ?? 0);

        if (!$idOffre) {
            echo json_encode([
                'success' => false,
                'message' => 'ID offre manquant'
            ]);
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                DELETE c FROM Contenir c
                JOIN Wishlists w ON c.ID_Wishlist = w.ID_Wishlist
                WHERE w.ID_Utilisateur = :user
                  AND c.ID_Offre = :offre
            ");

            $stmt->execute([
                ':user'  => $idUtilisateur,
                ':offre' => $idOffre
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Offre retirée de la wishlist'
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur'
            ]);
        }
    }
}
