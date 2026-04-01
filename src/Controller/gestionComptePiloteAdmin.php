<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionComptePiloteAdmin
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAjax();
            return;
        }

        $stmt = $this->pdo->query("
            SELECT ID_Utilisateur, Nom, Prenom, Email, Role
            FROM Utilisateurs
            WHERE Role = 2
            ORDER BY Nom ASC, Prenom ASC
        ");

        $pilotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->twig->render('gestionComptePilote_admin.html.twig', [
            'page'     => 'gestion_pilote_admin',
            'title'    => 'Gestion des comptes pilotes',
            'pilotes'  => $pilotes,
            'app_user' => AppUser::fromSession(),
        ]);
    }

    private function handleAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!is_array($input) || !isset($input['action'])) {
                $this->json([
                    'success' => false,
                    'message' => 'Requête invalide.'
                ]);
                return;
            }

            $action = (string)($input['action'] ?? '');

            if ($action === 'create') {
                $this->createPilote($input);
                return;
            }

            if ($action === 'update') {
                $this->updatePilote($input);
                return;
            }

            if ($action === 'delete') {
                $this->deletePilote($input);
                return;
            }

            if ($action === 'search') {
                $this->searchPilote($input);
                return;
            }

            $this->json([
                'success' => false,
                'message' => 'Action inconnue.'
            ]);
        } catch (Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
        }
    }

    private function createPilote(array $input): void
    {
        $prenom = trim((string)($input['prenom'] ?? ''));
        $nom    = trim((string)($input['nom'] ?? ''));
        $email  = trim((string)($input['email'] ?? ''));
        $mdp    = trim((string)($input['mdp'] ?? ''));

        if ($prenom === '' || $nom === '' || $email === '' || $mdp === '') {
            $this->json([
                'success' => false,
                'message' => 'Tous les champs sont obligatoires.'
            ]);
            return;
        }

        $check = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM Utilisateurs
            WHERE Email = :email
        ");
        $check->execute([
            ':email' => $email
        ]);

        if ((int)$check->fetchColumn() > 0) {
            $this->json([
                'success' => false,
                'message' => 'Cet email existe déjà.'
            ]);
            return;
        }

        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO Utilisateurs (Nom, Prenom, Email, Mdp, Role)
            VALUES (:nom, :prenom, :email, :mdp, :role)
        ");

        $stmt->execute([
            ':nom'    => $nom,
            ':prenom' => $prenom,
            ':email'  => $email,
            ':mdp'    => $mdpHash,
            ':role'   => 2
        ]);

        $id = (int)$this->pdo->lastInsertId();

        $pilote = [
            'ID_Utilisateur' => $id,
            'Nom'            => $nom,
            'Prenom'         => $prenom,
            'Email'          => $email,
            'Role'           => 2
        ];

        $this->json([
            'success' => true,
            'message' => 'Compte pilote créé avec succès.',
            'pilote'  => $pilote
        ]);
    }

    private function updatePilote(array $input): void
    {
        $id     = (int)($input['id'] ?? 0);
        $prenom = trim((string)($input['prenom'] ?? ''));
        $nom    = trim((string)($input['nom'] ?? ''));
        $email  = trim((string)($input['email'] ?? ''));

        if ($id <= 0 || $prenom === '' || $nom === '' || $email === '') {
            $this->json([
                'success' => false,
                'message' => 'Données invalides pour la modification.'
            ]);
            return;
        }

        $check = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM Utilisateurs
            WHERE Email = :email
              AND ID_Utilisateur != :id
        ");
        $check->execute([
            ':email' => $email,
            ':id'    => $id
        ]);

        if ((int)$check->fetchColumn() > 0) {
            $this->json([
                'success' => false,
                'message' => 'Cet email est déjà utilisé.'
            ]);
            return;
        }

        $stmt = $this->pdo->prepare("
            UPDATE Utilisateurs
            SET Nom = :nom,
                Prenom = :prenom,
                Email = :email
            WHERE ID_Utilisateur = :id
              AND Role = 2
        ");

        $stmt->execute([
            ':nom'    => $nom,
            ':prenom' => $prenom,
            ':email'  => $email,
            ':id'     => $id
        ]);

        if ($stmt->rowCount() === 0) {
            $exists = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM Utilisateurs
                WHERE ID_Utilisateur = :id
                  AND Role = 2
            ");
            $exists->execute([
                ':id' => $id
            ]);

            if ((int)$exists->fetchColumn() === 0) {
                $this->json([
                    'success' => false,
                    'message' => 'Aucun pilote trouvé à modifier.'
                ]);
                return;
            }
        }

        $pilote = [
            'ID_Utilisateur' => $id,
            'Nom'            => $nom,
            'Prenom'         => $prenom,
            'Email'          => $email,
            'Role'           => 2
        ];

        $this->json([
            'success' => true,
            'message' => 'Compte modifié avec succès.',
            'pilote'  => $pilote
        ]);
    }

    private function deletePilote(array $input): void
    {
        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            $this->json([
                'success' => false,
                'message' => 'ID invalide.'
            ]);
            return;
        }

        $stmt = $this->pdo->prepare("
            DELETE FROM Utilisateurs
            WHERE ID_Utilisateur = :id
              AND Role = 2
        ");
        $stmt->execute([
            ':id' => $id
        ]);

        if ($stmt->rowCount() === 0) {
            $this->json([
                'success' => false,
                'message' => 'Aucun pilote trouvé à supprimer.'
            ]);
            return;
        }

        $this->json([
            'success' => true,
            'message' => 'Compte supprimé avec succès.'
        ]);
    }

    private function searchPilote(array $input): void
    {
        $terme = trim((string)($input['terme'] ?? ''));

        $stmt = $this->pdo->prepare("
            SELECT ID_Utilisateur, Nom, Prenom, Email, Role
            FROM Utilisateurs
            WHERE Role = 2
              AND (
                    Nom LIKE :terme
                 OR Prenom LIKE :terme
                 OR Email LIKE :terme
              )
            ORDER BY Nom ASC, Prenom ASC
        ");

        $stmt->execute([
            ':terme' => '%' . $terme . '%'
        ]);

        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->json([
            'success'   => true,
            'resultats' => $resultats
        ]);
    }

    private function json(array $data): void
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
